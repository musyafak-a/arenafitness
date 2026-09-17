<?php

use App\Http\Controllers\Admin\DailyPassController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin - Manajemen Daily Pass (Tamu Harian)
|--------------------------------------------------------------------------
*/

Route::get('/daily-passes', [DailyPassController::class, 'index'])->name('daily-passes');
Route::post('/daily-passes', [DailyPassController::class, 'store'])->name('daily-passes.store');
Route::delete('/daily-passes/{dailyPass}', [DailyPassController::class, 'destroy'])->name('daily-passes.destroy');
