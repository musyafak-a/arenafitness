<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use App\Models\Transaction;
use App\Models\MembershipSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Generate Snap token only — no database record yet.
     * Transaction is created only after member commits to a payment method.
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'membership_plan_id' => 'required|exists:membership_plans,id',
        ]);

        $plan = MembershipPlan::findOrFail($request->membership_plan_id);
        
        if (!session('auth.id') || session('auth.role') !== 'member') {
            return response()->json(['error' => 'Anda belum login.'], 401);
        }

        $user = \App\Models\User::with('member')->find(session('auth.id'));
        if (!$user || !$user->member) {
            return response()->json(['error' => 'Anda bukan member.'], 403);
        }
        
        $member = $user->member;

        $invoiceId = 'INV-' . time() . '-' . Str::random(5);

        $params = [
            'transaction_details' => [
                'order_id' => $invoiceId,
                'gross_amount' => $plan->price,
            ],
            'customer_details' => [
                'first_name' => $member->full_name,
                'email' => $member->email ?? $user->email,
                'phone' => $member->phone,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            // Store checkout data in session so we can create the record later
            session()->put('pending_checkout', [
                'invoice' => $invoiceId,
                'snap_token' => $snapToken,
                'member_id' => $member->id,
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
                'plan_price' => $plan->price,
                'plan_duration' => $plan->duration_months,
                'customer_name' => $member->full_name,
            ]);

            return response()->json([
                'snap_token' => $snapToken,
                'invoice' => $invoiceId,
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Called by JS after member selects a payment method (onPending/onSuccess).
     * This is when we actually save the transaction to the database.
     */
    public function commitTransaction(Request $request)
    {
        $request->validate([
            'invoice' => 'required|string',
        ]);

        if (!session('auth.id') || session('auth.role') !== 'member') {
            return response()->json(['error' => 'Anda belum login.'], 401);
        }

        $checkout = session('pending_checkout');
        if (!$checkout || $checkout['invoice'] !== $request->invoice) {
            return response()->json(['error' => 'Data checkout tidak ditemukan.'], 404);
        }

        // Prevent duplicate commit
        $exists = Transaction::where('invoice', $checkout['invoice'])->exists();
        if ($exists) {
            session()->forget('pending_checkout');
            return response()->json(['status' => 'already_committed']);
        }

        $member = \App\Models\Member::find($checkout['member_id']);
        if (!$member) {
            return response()->json(['error' => 'Member tidak ditemukan.'], 404);
        }

        // NOW create the transaction record
        Transaction::create([
            'invoice' => $checkout['invoice'],
            'member_id' => $checkout['member_id'],
            'type' => Transaction::TYPE_MEMBERSHIP,
            'customer_name' => $checkout['customer_name'],
            'description' => 'Pembayaran ' . $checkout['plan_name'],
            'amount' => $checkout['plan_price'],
            'payment_method' => 'midtrans',
            'payment_status' => 'pending',
            'snap_token' => $checkout['snap_token'],
            'transaction_at' => now(),
        ]);

        // NOW create the subscription record
        $startDate = $member->expires_at && $member->expires_at->isFuture() 
            ? $member->expires_at->addDay() 
            : today();
            
        $endDate = $startDate->copy()->addMonths($checkout['plan_duration']);

        MembershipSubscription::create([
            'member_id' => $checkout['member_id'],
            'membership_plan_id' => $checkout['plan_id'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'amount_paid' => $checkout['plan_price'],
            'payment_method' => 'midtrans',
            'status' => 'pending', 
        ]);

        session()->forget('pending_checkout');

        return response()->json(['status' => 'committed']);
    }

    /**
     * Cancel a pending transaction (from billing history).
     */
    public function cancelTransaction(Request $request, $invoice)
    {
        if (!session('auth.id') || session('auth.role') !== 'member') {
            return redirect()->route('member.login');
        }

        $user = \App\Models\User::with('member')->find(session('auth.id'));
        if (!$user || !$user->member) {
            return redirect()->route('member.membership')->withErrors(['error' => 'Data member tidak ditemukan.']);
        }

        $member = $user->member;

        $transaction = Transaction::where('invoice', $invoice)
            ->where('member_id', $member->id)
            ->where('payment_status', 'pending')
            ->firstOrFail();

        $transaction->update(['payment_status' => 'cancelled']);

        // Also cancel the associated subscription
        MembershipSubscription::where('member_id', $member->id)
            ->where('status', 'pending')
            ->where('created_at', '>=', $transaction->created_at->subMinute())
            ->where('created_at', '<=', $transaction->created_at->addMinute())
            ->update(['status' => 'cancelled']);

        return redirect()->route('member.membership')->with('status', 'Transaksi ' . $invoice . ' berhasil dibatalkan.');
    }

    public function invoice($invoice)
    {
        $transaction = Transaction::where('invoice', $invoice)->firstOrFail();
        
        return view('member.transaction.invoice', compact('transaction'));
    }
}
