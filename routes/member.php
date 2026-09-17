<?php

use App\Http\Controllers\Member\MemberPortalController;
use App\Models\Announcement;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

/*
|--------------------------------------------------------------------------
| Member Portal Routes
|--------------------------------------------------------------------------
*/

View::composer('member.*', function ($view) {
    if (! session('auth.role') || session('auth.role') !== 'member') {
        return;
    }

    $user = User::query()->where('id', session('auth.id'))->where('role', 'member')->first();
    $member = $user?->member;

    $announcements = collect();
    if ($member) {
        $announcements = Announcement::query()
            ->where('status', '!=', 'archived')
            ->where('body', 'like', "[TARGET_MEMBER_ID:{$member->id}]%")
            ->latest('publish_at')
            ->get();
    }

    $membershipWarning = null;
    if ($member?->expires_at) {
        $daysLeft = now()->startOfDay()->diffInDays($member->expires_at->copy()->startOfDay(), false);

        if ($daysLeft >= 0 && $daysLeft <= 7) {
            $membershipWarning = [
                'days_left' => (int) $daysLeft,
                'title'     => $daysLeft <= 3 ? 'Peringatan Membership Mendesak' : 'Peringatan Membership',
                'message'   => $daysLeft === 0
                    ? 'Masa aktif membership Anda berakhir hari ini. Silakan hubungi kasir untuk perpanjangan.'
                    : "Masa aktif membership Anda tersisa {$daysLeft} hari. Silakan hubungi kasir untuk perpanjangan.",
                'level'     => $daysLeft <= 3 ? 'urgent' : 'warning',
            ];
        }
    }

    $channelUrl = config('services.whatsapp.channel_url');
    $showWhatsAppChannelPrompt = (bool) session()->pull('show_whatsapp_channel_prompt', false);

    $view->with([
        'memberAnnouncements'       => $announcements,
        'announcements'             => $announcements,
        'membershipWarning'         => $membershipWarning,
        'channelUrl'                => $channelUrl,
        'showWhatsAppChannelPrompt' => $showWhatsAppChannelPrompt,
        'hasMembershipWarning'      => filled($membershipWarning),
        'shouldShowChannelPrompt'   => $showWhatsAppChannelPrompt && filled($channelUrl),
    ]);
});

// ─── Guest / Auth Routes ───────────────────────────────────────────────────
Route::get('/member/login', [MemberPortalController::class, 'showLogin'])->name('member.login');
Route::post('/member/login', [MemberPortalController::class, 'submitLogin'])->name('member.login.submit');

Route::get('/member/activate', [MemberPortalController::class, 'showActivate'])->name('member.activate.show');
Route::post('/member/activate', [MemberPortalController::class, 'submitActivate'])->name('member.activate.store');

Route::get('/forgot-password', [MemberPortalController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [MemberPortalController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [MemberPortalController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [MemberPortalController::class, 'updatePassword'])->name('password.update');

// ─── Authenticated Member Pages ─────────────────────────────────────────────
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [MemberPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/statistics', [MemberPortalController::class, 'statistics'])->name('statistics');
    Route::get('/messages', [MemberPortalController::class, 'messages'])->name('messages');
    Route::get('/history', [MemberPortalController::class, 'history'])->name('history');
    Route::get('/barcode', [MemberPortalController::class, 'barcode'])->name('barcode');
    Route::get('/membership', [MemberPortalController::class, 'membership'])->name('membership');

    Route::get('/profile', [MemberPortalController::class, 'profile'])->name('profile');
    Route::post('/profile', [MemberPortalController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/photo', [MemberPortalController::class, 'updatePhoto'])->name('profile.photo.update');

    Route::post('/feedback', [MemberPortalController::class, 'submitFeedback'])->name('feedback.submit');
    Route::post('/logout', [MemberPortalController::class, 'logout'])->name('logout');
});
