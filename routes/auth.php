<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
| Menangani seluruh alur login (multi-role) dan logout via AuthController.
*/

// Root redirect
Route::get('/', [AuthController::class, 'rootRedirect']);

// Login page
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Role-specific login shortcuts
Route::get('/login/member',        fn () => redirect()->route('member.login'))->name('login.member');
Route::get('/login/admin',         fn () => redirect()->route('login', ['role' => 'admin']))->name('admin.login');
Route::post('/login/admin',        fn () => redirect()->route('login', ['role' => 'admin']))->name('admin.login.submit');

Route::get('/login/cashier',       fn () => redirect()->route('login', ['role' => 'cashier']))->name('cashier.login');
Route::post('/login/cashier',      fn () => redirect()->route('login', ['role' => 'cashier']))->name('cashier.login.submit');

Route::get('/login/master-admin',  fn () => redirect()->route('login', ['role' => 'master_admin']))->name('master-admin.login');
Route::post('/login/master-admin', fn () => redirect()->route('login', ['role' => 'master_admin']))->name('master-admin.login.submit');

// Login submit & Logout
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
