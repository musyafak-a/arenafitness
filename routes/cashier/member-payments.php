<?php

use App\Http\Controllers\Cashier\MemberPaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cashier – Pembayaran Membership
|--------------------------------------------------------------------------
*/

Route::get('/member-payments', [MemberPaymentController::class, 'index'])->name('member-payments');
Route::post('/member-payments', [MemberPaymentController::class, 'store'])->name('member-payments.store');
