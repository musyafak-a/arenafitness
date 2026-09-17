<?php

use App\Http\Controllers\Member\SelfServiceCheckinController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Member Self-Service Check-in (QR)
|--------------------------------------------------------------------------
*/

Route::get('/member-checkin', [SelfServiceCheckinController::class, 'show'])->name('member.checkin');
Route::post('/member-checkin', [SelfServiceCheckinController::class, 'store'])->name('member.checkin.store');
