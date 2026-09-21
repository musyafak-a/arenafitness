<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipPlan;
use App\Models\Transaction;
use App\Models\MembershipSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans Webhook Received:', $payload);

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;

        $serverKey = config('services.midtrans.server_key');
        
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($expectedSignature !== $signatureKey) {
            Log::error('Midtrans Signature Mismatch', [
                'expected' => $expectedSignature,
                'received' => $signatureKey,
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaction = Transaction::where('invoice', $orderId)->first();
        
        // If transaction doesn't exist yet (webhook arrived before commitTransaction),
        // we need to handle this gracefully
        if (!$transaction) {
            Log::warning('Midtrans webhook: Transaction not found for order ' . $orderId . '. Status: ' . $transactionStatus);
            
            // For settlement/capture, we should not lose this event
            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                Log::error('CRITICAL: Payment confirmed but transaction record missing for ' . $orderId);
            }
            
            return response()->json(['message' => 'Transaction not found, will retry'], 404);
        }

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $transaction->payment_status = 'verified';
            $transaction->payment_method = $paymentType;
            $transaction->paid_amount = $transaction->amount;
            
            // Update subscription if it's a membership payment
            if ($transaction->type === Transaction::TYPE_MEMBERSHIP) {
                $subscription = MembershipSubscription::where('member_id', $transaction->member_id)
                    ->where('status', 'pending')
                    ->latest()
                    ->first();
                
                if ($subscription) {
                    $subscription->status = 'active';
                    $subscription->payment_method = $paymentType;
                    $subscription->save();

                    // Update Member's expires_at
                    $member = $transaction->member;
                    if ($member) {
                        $member->expires_at = $subscription->end_date;
                        $member->save();
                    }
                }
            }
        } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $transaction->payment_status = 'cancelled';
            
            if ($transaction->type === Transaction::TYPE_MEMBERSHIP) {
                $subscription = MembershipSubscription::where('member_id', $transaction->member_id)
                    ->where('status', 'pending')
                    ->latest()
                    ->first();
                
                if ($subscription) {
                    $subscription->status = 'cancelled';
                    $subscription->save();
                }
            }
        } elseif ($transactionStatus == 'pending') {
            $transaction->payment_status = 'pending';
            $transaction->payment_method = $paymentType;
        }

        $transaction->save();

        return response()->json(['message' => 'OK']);
    }
}
