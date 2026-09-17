<?php

use App\Http\Controllers\Cashier\ReceiptController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cashier – Bukti Pembayaran & Verifikasi QRIS
|--------------------------------------------------------------------------
*/

Route::get('/verifications', [ReceiptController::class, 'verificationsRedirect'])->name('verifications');
Route::post('/verifications/{paymentId}', [ReceiptController::class, 'confirmVerification'])->name('verifications.confirm');
Route::get('/receipts', [ReceiptController::class, 'index'])->name('receipts');
Route::get('/receipts/{invoice}/print', [ReceiptController::class, 'printReceipt'])->name('receipts.print');
