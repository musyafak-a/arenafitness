<?php

use App\Helpers\RouteHelpers;
use App\Models\Member;
use App\Models\MembershipPlan;
use App\Models\MembershipSubscription;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cashier – Pembayaran Membership
|--------------------------------------------------------------------------
*/

// ── Index ─────────────────────────────────────────────────────────────────────
Route::get('/member-payments', function (Request $request) {
    if ($redirect = RouteHelpers::ensureCashier()) {
        return $redirect;
    }

    $memberSearch = trim((string) $request->query('member_q', ''));
    $members = Member::query()
        ->when($memberSearch !== '', function ($query) use ($memberSearch) {
            $query->where(function ($q) use ($memberSearch) {
                $q->where('full_name', 'like', '%' . $memberSearch . '%')
                    ->orWhere('phone', 'like', '%' . $memberSearch . '%')
                    ->orWhere('email', 'like', '%' . $memberSearch . '%')
                    ->orWhere('checkin_code', 'like', '%' . $memberSearch . '%');
            });
        })
        ->orderBy('full_name')
        ->paginate(10, ['*'], 'members_page')
        ->withQueryString();

    $viewData = RouteHelpers::buildCashierViewData([
        'pageTitle'  => 'Pembayaran Member - Kasir Arena Gym',
        'activePage' => 'cashier.member-payments',
    ]);

    // Pagination dengan limit 10 data per halaman
    $memberPayments = collect($viewData['memberPayments'])
        ->filter(fn (Transaction $t) => $t->transaction_at && $t->transaction_at->gte(now()->subDay()))
        ->values();

    // Konversi collection ke paginated result
    $perPage = 10;
    $page = max((int) $request->query('page', 1), 1);
    $paginatedPayments = new \Illuminate\Pagination\LengthAwarePaginator(
        $memberPayments->forPage($page, $perPage)->values(),
        $memberPayments->count(),
        $perPage,
        $page,
        [
            'path' => route('cashier.member-payments'),
            'query' => $request->query(),
        ]
    );

    return view('cashier.member-payments', array_merge($viewData, [
        'members' => $members,
        'memberSearch' => $memberSearch,
        'memberPayments' => $paginatedPayments,
    ]));
})->name('member-payments');

// ── Store ─────────────────────────────────────────────────────────────────────
Route::post('/member-payments', function (Request $request) {
    if ($redirect = RouteHelpers::ensureCashier()) {
        return $redirect;
    }

    $validated = $request->validate([
        'gym_member_id'  => ['nullable', 'exists:members,id'],
        'member_id'      => ['nullable', 'exists:members,id'],
        'amount'         => ['nullable', 'integer'],
        'paid_amount'    => ['nullable', 'integer', 'min:0'],
        'payment_method' => ['required', 'in:cash,qris'],
        'notes'          => ['nullable', 'string'],
    ]);

    $memberId = $validated['member_id'] ?? $validated['gym_member_id'];
    if (! $memberId) {
        return back()->withErrors(['member_id' => 'Member wajib dipilih.']);
    }

    $plan = MembershipPlan::where('duration_months', 1)->first();
    $membershipAmount = $plan?->price ?? 90000;
    $paidAmount       = $validated['payment_method'] === 'qris'
        ? $membershipAmount
        : (int) ($validated['paid_amount'] ?? 0);

    if ($validated['payment_method'] === 'cash' && $paidAmount < $membershipAmount) {
        return back()
            ->withErrors(['paid_amount' => 'Uang diterima tidak boleh kurang dari nominal pembayaran.'])
            ->withInput();
    }

    $changeAmount     = max($paidAmount - $membershipAmount, 0);
    $paymentStatus    = $validated['payment_method'] === 'cash' ? 'verified' : 'pending';
    $member           = Member::query()->findOrFail($memberId);

    // Jika tunai → langsung perpanjang membership
    if ($paymentStatus === 'verified') {
        $startDate = ($member->expires_at && Carbon::parse($member->expires_at)->isFuture())
            ? Carbon::parse($member->expires_at)
            : now();
        $newExpiresAt = Carbon::parse(RouteHelpers::calculateMembershipRenewalExpiry($member, Carbon::today()));

        $member->update([
            'expires_at' => $newExpiresAt,
        ]);

        MembershipSubscription::create([
            'member_id'          => $member->id,
            'membership_plan_id' => $plan?->id,
            'start_date'         => $startDate,
            'end_date'           => $newExpiresAt,
            'amount_paid'        => $membershipAmount,
            'payment_method'     => $validated['payment_method'],
            'status'             => 'active',
        ]);
    }

    Transaction::create([
        'invoice'          => RouteHelpers::generateInvoice('MP'),
        'member_id'        => $member->id,
        'cashier_user_id'  => RouteHelpers::authUserId(),
        'customer_name'    => $member->full_name,
        'type'             => Transaction::TYPE_MEMBERSHIP,
        'description'      => 'Perpanjangan Membership 1 Bulan',
        'amount'           => $membershipAmount,
        'paid_amount'      => $paidAmount,
        'change_amount'    => $changeAmount,
        'payment_method'   => $validated['payment_method'],
        'payment_status'   => $paymentStatus,
        'transaction_at'   => now(),
        'notes'            => $validated['notes'] ?? null,
    ]);

    return redirect()->route('cashier.transactions', ['section' => 'member'])
        ->with('status', 'Pembayaran member berhasil dicatat.');
})->name('member-payments.store');
