<?php

use App\Http\Controllers\Cashier\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cashier – Semua Transaksi & Penjualan Produk
|--------------------------------------------------------------------------
*/

Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
Route::get('/transactions/products', [TransactionController::class, 'products'])->name('transactions.products');
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');

Route::get('/transactions/register-member', [TransactionController::class, 'showRegisterMemberForm'])->name('transactions.register-member.form');
Route::post('/transactions/register-member', [TransactionController::class, 'registerMember'])->name('transactions.register-member');
