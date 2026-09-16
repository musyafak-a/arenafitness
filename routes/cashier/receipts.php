<?php

use App\Helpers\RouteHelpers;
use App\Models\Member;
use App\Models\MembershipPlan;
use App\Models\MembershipSubscription;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cashier – Bukti Pembayaran & Verifikasi QRIS
|--------------------------------------------------------------------------
*/

// ── Redirect lama /verifications → /receipts ──────────────────────────────────
Route::get('/verifications', function () {
    if ($redirect = RouteHelpers::ensureCashier()) {
        return $redirect;
    }

    return redirect()->route('cashier.receipts');
})->name('verifications');

// ── Verifikasi QRIS ───────────────────────────────────────────────────────────
Route::post('/verifications/{paymentId}', function (Request $request, int $paymentId) {
    if ($redirect = RouteHelpers::ensureCashier()) {
        return $redirect;
    }

    $transaction = Transaction::query()->findOrFail($paymentId);

    $transaction->update([
        'payment_status' => 'verified',
        'paid_amount' => $transaction->paid_amount ?? $transaction->amount,
        'change_amount' => $transaction->change_amount ?? 0,
    ]);

    // Jika pembayaran membership → perpanjang masa aktif member dan buat subscription
    if ($transaction->type === Transaction::TYPE_MEMBERSHIP && $transaction->member_id) {
        $member = Member::query()->find($transaction->member_id);

        if ($member) {
            $startDate = ($member->expires_at && Carbon::parse($member->expires_at)->isFuture())
                ? Carbon::parse($member->expires_at)
                : now();
            $newExpiresAt = Carbon::parse(RouteHelpers::calculateMembershipRenewalExpiry($member, Carbon::today()));

            $member->update([
                'expires_at' => $newExpiresAt,
            ]);

            $plan = MembershipPlan::where('duration_months', 1)->first();

            MembershipSubscription::create([
                'member_id'          => $member->id,
                'membership_plan_id' => $plan?->id,
                'start_date'         => $startDate,
                'end_date'           => $newExpiresAt,
                'amount_paid'        => $transaction->amount,
                'payment_method'     => $transaction->payment_method ?? 'qris',
                'status'             => 'active',
            ]);
        }
    }

    $redirectRoute = $request->input('return_to') === 'dashboard'
        ? 'cashier.dashboard'
        : 'cashier.receipts';

    return redirect()->route($redirectRoute)
        ->with('status', "Pembayaran {$transaction->invoice} berhasil diverifikasi.");
})->name('verifications.confirm');

// ── Daftar bukti pembayaran ───────────────────────────────────────────────────
Route::get('/receipts', function (Request $request) {
    if ($redirect = RouteHelpers::ensureCashier()) {
        return $redirect;
    }

    $viewData = RouteHelpers::buildCashierViewData([
        'pageTitle'  => 'Bukti Pembayaran - Kasir Arena Gym',
        'activePage' => 'cashier.receipts',
    ]);

    $search = trim((string) $request->query('q', ''));
    $perPage = 10;
    $receiptPage = max(1, (int) $request->query('receipt_page', 1));
    $receiptQuery = $request->query();
    unset($receiptQuery['receipt_page']);

    $receiptItems = collect($viewData['receiptQueue'])
        ->filter(fn (Transaction $t) => $t->payment_method === 'qris')
        ->when(
            $search !== '',
            fn ($col) => $col->filter(
                fn (Transaction $t) => str_contains(
                    str()->lower((string) $t->customer_name),
                    str()->lower($search)
                )
            )
        )
        ->values();

    $receiptQueue = new LengthAwarePaginator(
        $receiptItems->forPage($receiptPage, $perPage)->values(),
        $receiptItems->count(),
        $perPage,
        $receiptPage,
        [
            'path' => $request->url(),
            'pageName' => 'receipt_page',
            'query' => $receiptQuery,
        ]
    );

    return view('cashier.receipts', array_merge($viewData, [
        'receiptSearch'        => $search,
        'receiptQueue'         => $receiptQueue,
    ]));
})->name('receipts');

// ── Cetak bukti pembayaran ────────────────────────────────────────────────────
Route::get('/receipts/{invoice}/print', function (string $invoice) {
    if ($redirect = RouteHelpers::ensureCashier()) {
        return $redirect;
    }

    $receipt = Transaction::query()->where('invoice', $invoice)->firstOrFail();

    return view('cashier.receipt-print', [
        'pageTitle' => "Cetak Bukti {$invoice}",
        'receipt'   => $receipt,
    ]);
})->name('receipts.print');
