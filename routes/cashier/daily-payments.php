<?php

use App\Helpers\RouteHelpers;
use App\Models\Checkin;
use App\Models\DailyGuest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Cashier - Daily Pass
|--------------------------------------------------------------------------
*/

// ── Index ─────────────────────────────────────────────────────────────────────
Route::get('/daily-payments', function () {
    if ($redirect = RouteHelpers::ensureCashier()) {
        return $redirect;
    }

    $viewData = RouteHelpers::buildCashierViewData([
        'pageTitle'  => 'Daily Pass - Kasir Arena Gym',
        'activePage' => 'cashier.daily-payments',
    ]);

    return view('cashier.daily-payments', array_merge($viewData, [
        // Tampilkan hanya 24 jam terakhir
        'dailyPayments' => collect($viewData['dailyPayments'])
            ->filter(fn (Transaction $t) => $t->transaction_at && $t->transaction_at->gte(now()->subDay()))
            ->values(),
    ]));
})->name('daily-payments');

// ── Store ─────────────────────────────────────────────────────────────────────
Route::post('/daily-payments', function (Request $request) {
    if ($redirect = RouteHelpers::ensureCashier()) {
        return $redirect;
    }

    $validated = $request->validate([
        'customer_name'  => ['required', 'string', 'max:255'],
        'amount'         => ['required', 'integer', 'min:1'],
        'paid_amount'    => ['nullable', 'integer', 'min:0'],
        'payment_method' => ['required', 'in:cash,qris'],
        'notes'          => ['nullable', 'string'],
    ]);

    $paymentStatus = $validated['payment_method'] === 'cash' ? 'verified' : 'pending';
    $paidAmount = $validated['payment_method'] === 'qris'
        ? (int) $validated['amount']
        : (int) ($validated['paid_amount'] ?? 0);

    if ($validated['payment_method'] === 'cash' && $paidAmount < (int) $validated['amount']) {
        return back()
            ->withErrors(['paid_amount' => 'Uang diterima tidak boleh kurang dari nominal pembayaran.'])
            ->withInput();
    }

    $changeAmount = max($paidAmount - (int) $validated['amount'], 0);

    // Create DailyGuest record
    $guest = DailyGuest::create([
        'full_name'  => $validated['customer_name'],
        'visit_type' => 'daily_pass',
        'visit_at'   => now(),
    ]);

    // Record checkin for daily guest
    Checkin::create([
        'daily_guest_id'      => $guest->id,
        'checked_in_at'       => now(),
        'checkin_method'      => 'cashier',
        'verification_status' => 'verified',
        'verified_at'         => now(),
        'verified_by'         => RouteHelpers::authUserId(),
    ]);

    $transaction = Transaction::create([
        'invoice'         => RouteHelpers::generateInvoice('DP'),
        'daily_guest_id'  => $guest->id,
        'cashier_user_id' => RouteHelpers::authUserId(),
        'customer_name'   => $validated['customer_name'],
        'type'            => Transaction::TYPE_DAILY_PASS,
        'description'     => 'Daily Pass',
        'amount'          => $validated['amount'],
        'paid_amount'     => $paidAmount,
        'change_amount'   => $changeAmount,
        'payment_method'  => $validated['payment_method'],
        'payment_status'  => $paymentStatus,
        'transaction_at'  => now(),
        'notes'           => $validated['notes'] ?? null,
    ]);

    if ($validated['payment_method'] === 'cash') {
        return redirect()->route('cashier.transactions', ['section' => 'daily_pass'])
            ->with('status', "Pembayaran tunai {$transaction->invoice} berhasil dicatat. Struk bisa dicetak kapan saja dari daftar transaksi.");
    }

    return redirect()->route('cashier.dashboard')
        ->with('status', 'Pembayaran QRIS berhasil dicatat. Verifikasi setelah pembayaran terlihat masuk.');
})->name('daily-payments.store');
