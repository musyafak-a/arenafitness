<?php

use App\Http\Controllers\Member\MemberPortalController;
use Illuminate\Support\Facades\Route;

Route::get('/member-profile-photos/{path}', [MemberPortalController::class, 'showProfilePhoto'])
    ->where('path', '.*')
    ->name('member.profile-photo.show');
