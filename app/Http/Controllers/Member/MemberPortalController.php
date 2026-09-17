<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Member;
use App\Models\MemberFeedback;
use App\Models\ProfilePhotoChangeRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MemberPortalController extends Controller
{
    /**
     * Check if a member's membership is still active.
     */
    protected function isMemberMembershipActive(?Member $member): bool
    {
        return (bool) ($member?->expires_at && $member->expires_at->copy()->startOfDay()->gte(now()->startOfDay()));
    }

    /**
     * Inactive membership redirect helper.
     */
    protected function inactiveMembershipRedirect(): RedirectResponse
    {
        return redirect()
            ->route('member.login')
            ->withErrors(['email' => 'Masa aktif membership Anda sudah habis. Silakan perpanjang di kasir agar akun bisa dibuka kembali.']);
    }

    /**
     * Validate and retrieve authenticated member and user from session.
     */
    protected function getActiveMemberSession(Request $request): array
    {
        if (! session('auth.role') || session('auth.role') !== 'member') {
            return ['redirect' => redirect()->route('member.login')];
        }

        $user = User::query()->with('member')->where('id', session('auth.id'))->where('role', 'member')->first();
        if (! $user) {
            $request->session()->forget(['auth', 'show_whatsapp_channel_prompt']);
            return ['redirect' => redirect()->route('member.login')->withErrors(['email' => 'Sesi login berakhir, silakan login kembali.'])];
        }

        $member = $user->member;
        if (! $this->isMemberMembershipActive($member)) {
            $request->session()->forget(['auth', 'show_whatsapp_channel_prompt']);
            return ['redirect' => $this->inactiveMembershipRedirect()];
        }

        return ['user' => $user, 'member' => $member];
    }

    /**
     * Show member login page.
     */
    public function showLogin(): View|RedirectResponse
    {
        $authId = session('auth.id');
        $authRole = session('auth.role');
        if ($authId && $authRole === 'member') {
            $existingUser = User::query()->where('id', $authId)->where('role', 'member')->first();
            if ($existingUser) {
                return redirect()->route('member.dashboard');
            }
            session()->forget('auth');
        }

        return view('member.login');
    }

    /**
     * Process member login.
     */
    public function submitLogin(Request $request): RedirectResponse
    {
        $authId = session('auth.id');
        $authRole = session('auth.role');
        if ($authId && $authRole === 'member') {
            $existingUser = User::query()->where('id', $authId)->where('role', 'member')->first();
            if ($existingUser) {
                return redirect()->route('member.dashboard');
            }
            $request->session()->forget('auth');
        }

        $validated = $request->validate([
            'email'    => ['required', 'string', 'max:255'],
            'password' => ['required'],
        ]);

        $user = User::query()
            ->where(function ($query) use ($validated) {
                $query->where('email', $validated['email'])
                      ->orWhere('login', $validated['email']);
            })
            ->where('role', 'member')
            ->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return back()
                ->withErrors(['email' => 'Email/Username atau password tidak sesuai.'])
                ->withInput();
        }

        if (! $this->isMemberMembershipActive($user->member)) {
            return $this->inactiveMembershipRedirect()->withInput($request->only('email'));
        }

        $request->session()->regenerate();
        $request->session()->put('auth', [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => 'member',
        ]);
        $request->session()->put('show_whatsapp_channel_prompt', true);

        return redirect()->route('member.dashboard');
    }

    /**
     * Show member account activation form.
     */
    public function showActivate(Request $request): View
    {
        $memberSearch = trim((string) $request->query('q', ''));

        $activationMembers = Member::query()
            ->with('user')
            ->when($memberSearch !== '', function ($query) use ($memberSearch) {
                $query->where(function ($q) use ($memberSearch) {
                    $q->where('full_name', 'like', '%' . $memberSearch . '%')
                        ->orWhere('email', 'like', '%' . $memberSearch . '%')
                        ->orWhere('phone', 'like', '%' . $memberSearch . '%')
                        ->orWhere('checkin_code', 'like', '%' . $memberSearch . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('member.activate', [
            'activationMembers' => $activationMembers,
            'memberSearch'      => $memberSearch,
        ]);
    }

    /**
     * Process member account activation.
     */
    public function submitActivate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $memberUser = User::query()
            ->where('email', $validated['email'])
            ->where('role', 'member')
            ->first();

        if (! $memberUser) {
            return back()
                ->withErrors(['email' => 'Email member tidak ditemukan.'])
                ->withInput();
        }

        User::query()->where('id', $memberUser->id)->update([
            'password'   => Hash::make($validated['password']),
            'updated_at' => now(),
        ]);

        return redirect()->route('member.login')->with('status', 'Password berhasil dibuat. Silakan login.');
    }

    /**
     * Show forgot password form.
     */
    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link to member email.
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::query()
            ->where('email', $validated['email'])
            ->where('role', 'member')
            ->first();

        if (! $user) {
            return back()
                ->withErrors(['email' => 'Email member tidak ditemukan.'])
                ->withInput();
        }

        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token'      => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $resetLink = route('password.reset', ['token' => $token, 'email' => $user->email]);

        Mail::raw("Gunakan link berikut untuk reset password Arena Fitness:\n\n{$resetLink}\n\nLink berlaku 60 menit.", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Reset Password Arena Fitness');
        });

        return back()
            ->with('status', 'Link reset password sudah dibuat.')
            ->with('reset_link', app()->environment('local') ? $resetLink : null);
    }

    /**
     * Show reset password form.
     */
    public function showResetPassword(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Update member password using reset token.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token'    => ['required', 'string'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->first();

        if (! $record || ! Hash::check($validated['token'], $record->token)) {
            return back()
                ->withErrors(['email' => 'Token reset password tidak valid.'])
                ->withInput();
        }

        if ($record->created_at && now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

            return back()
                ->withErrors(['email' => 'Token reset password sudah kedaluwarsa.'])
                ->withInput();
        }

        $user = User::query()
            ->where('email', $validated['email'])
            ->where('role', 'member')
            ->first();

        if (! $user) {
            return back()
                ->withErrors(['email' => 'Email member tidak ditemukan.'])
                ->withInput();
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

        return redirect()
            ->route('member.login')
            ->with('status', 'Password berhasil diperbarui. Silakan login dengan password baru.');
    }

    /**
     * Display member dashboard.
     */
    public function dashboard(Request $request): View|RedirectResponse
    {
        $session = $this->getActiveMemberSession($request);
        if (isset($session['redirect'])) {
            return $session['redirect'];
        }

        $user = $session['user'];
        $member = $session['member'];
        $totalCheckins = $member ? $member->verifiedCheckins()->count() : 0;
        $membershipStatus = 'member';

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

        return view('member.dashboard', [
            'user'              => $user,
            'member'            => $member,
            'totalCheckins'     => $totalCheckins,
            'membershipStatus'  => $membershipStatus,
            'announcements'     => $announcements,
            'membershipWarning' => $membershipWarning,
        ]);
    }

    /**
     * Display member statistics.
     */
    public function statistics(Request $request): View|RedirectResponse
    {
        $session = $this->getActiveMemberSession($request);
        if (isset($session['redirect'])) {
            return $session['redirect'];
        }

        $user = $session['user'];
        $member = $session['member'];

        $totalCheckins = $member ? $member->verifiedCheckins()->count() : 0;
        $thisMonthCheckins = $member
            ? $member->verifiedCheckins()->whereMonth('checked_in_at', now()->month)->whereYear('checked_in_at', now()->year)->count()
            : 0;
        $lastCheckin = $member ? $member->verifiedCheckins()->latest('checked_in_at')->first()?->checked_in_at : null;

        $weekly = collect(range(6, 0))->map(function ($offset) use ($member) {
            $date = now()->copy()->subDays($offset);
            $count = $member
                ? $member->verifiedCheckins()
                    ->whereDate('checked_in_at', $date->toDateString())
                    ->count()
                : 0;
            return [
                'label' => $date->translatedFormat('D'),
                'value' => $count,
            ];
        });

        $monthly = collect(range(5, 0))->map(function ($offset) use ($member) {
            $date = now()->copy()->subMonths($offset);
            $count = $member
                ? $member->verifiedCheckins()
                    ->whereYear('checked_in_at', $date->year)
                    ->whereMonth('checked_in_at', $date->month)
                    ->count()
                : 0;
            return [
                'label' => $date->translatedFormat('M Y'),
                'value' => $count,
            ];
        });

        return view('member.statistics', [
            'user'              => $user,
            'member'            => $member,
            'totalCheckins'     => $totalCheckins,
            'thisMonthCheckins' => $thisMonthCheckins,
            'lastCheckin'       => $lastCheckin,
            'weeklyStats'       => $weekly,
            'monthlyStats'      => $monthly,
        ]);
    }

    /**
     * Display member messages and announcements.
     */
    public function messages(Request $request): View|RedirectResponse
    {
        $session = $this->getActiveMemberSession($request);
        if (isset($session['redirect'])) {
            return $session['redirect'];
        }

        $user = $session['user'];
        $member = $session['member'];
        $announcements = collect();
        if ($member) {
            $announcements = Announcement::query()
                ->where('status', '!=', 'archived')
                ->where('body', 'like', "[TARGET_MEMBER_ID:{$member->id}]%")
                ->latest('publish_at')
                ->get();
        }

        return view('member.messages', [
            'user'             => $user,
            'member'           => $member,
            'membershipStatus' => 'member',
            'announcements'    => $announcements,
        ]);
    }

    /**
     * Display member attendance history.
     */
    public function history(Request $request): View|RedirectResponse
    {
        $session = $this->getActiveMemberSession($request);
        if (isset($session['redirect'])) {
            return $session['redirect'];
        }

        $user = $session['user'];
        $member = $session['member'];
        $checkins = $member
            ? $member->verifiedCheckins()
                ->latest('checked_in_at')
                ->paginate(10)
            : collect();

        $totalCheckins = $member ? $member->verifiedCheckins()->count() : 0;
        $thisMonthCheckins = $member
            ? $member->verifiedCheckins()
                ->whereMonth('checked_in_at', now()->month)
                ->whereYear('checked_in_at', now()->year)
                ->count()
            : 0;
        $thisWeekCheckins = $member
            ? $member->verifiedCheckins()
                ->whereBetween('checked_in_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count()
            : 0;

        $chartStart = $member?->joined_at ? $member->joined_at->copy()->startOfDay() : now()->copy()->subDays(29)->startOfDay();
        $chartEnd = now()->copy()->startOfDay();
        if ($chartStart->diffInDays($chartEnd) > 30) {
            $chartStart = $chartEnd->copy()->subDays(29);
        }

        $chartCheckins = $member
            ? $member->verifiedCheckins()
                ->whereBetween('checked_in_at', [$chartStart->copy()->startOfDay(), $chartEnd->copy()->endOfDay()])
                ->get()
                ->groupBy(fn ($checkin) => $checkin->checked_in_at?->format('Y-m-d'))
            : collect();

        $trainingChartLabels = [];
        $trainingChartValues = [];
        for ($date = $chartStart->copy(); $date->lte($chartEnd); $date->addDay()) {
            $key = $date->format('Y-m-d');
            $trainingChartLabels[] = $date->translatedFormat('d M');
            $trainingChartValues[] = $chartCheckins->get($key, collect())->count();
        }

        $trackedDays = max(count($trainingChartValues), 1);
        $activeTrainingDays = collect($trainingChartValues)->filter(fn ($count) => $count > 0)->count();
        $trainingConsistency = (int) round(($activeTrainingDays / $trackedDays) * 100);
        $trainingAssessment = $trainingConsistency >= 50
            ? 'Rajin'
            : ($trainingConsistency >= 25 ? 'Cukup Konsisten' : 'Perlu Lebih Konsisten');

        return view('member.history', [
            'user'                => $user,
            'checkins'            => $checkins,
            'totalCheckins'       => $totalCheckins,
            'thisMonthCheckins'   => $thisMonthCheckins,
            'thisWeekCheckins'    => $thisWeekCheckins,
            'trainingChartLabels' => $trainingChartLabels,
            'trainingChartValues' => $trainingChartValues,
            'trainingConsistency' => $trainingConsistency,
            'trainingAssessment'  => $trainingAssessment,
            'activeTrainingDays'  => $activeTrainingDays,
            'trackedTrainingDays' => $trackedDays,
        ]);
    }

    /**
     * Display member barcode check-in card.
     */
    public function barcode(Request $request): View|RedirectResponse
    {
        $session = $this->getActiveMemberSession($request);
        if (isset($session['redirect'])) {
            return $session['redirect'];
        }

        $user = $session['user'];
        $member = $session['member'];
        $membershipStatus = 'member';

        return view('member.barcode', [
            'user'             => $user,
            'member'           => $member,
            'checkinCode'      => $member?->checkin_code,
            'membershipStatus' => $membershipStatus,
            'sessionId'        => 'ARENA-' . random_int(100000, 999999),
        ]);
    }

    /**
     * Display member active subscription and history.
     */
    public function membership(Request $request): View|RedirectResponse
    {
        $session = $this->getActiveMemberSession($request);
        if (isset($session['redirect'])) {
            return $session['redirect'];
        }

        $user = $session['user'];
        $member = $session['member'];
        $membershipStatus = 'member';

        $joinedAt = $member?->joined_at;
        $expiresAt = $member?->expires_at;
        $remainingDays = $expiresAt ? max(0, now()->startOfDay()->diffInDays($expiresAt, false)) : null;
        $totalDays = ($joinedAt && $expiresAt)
            ? max(1, $joinedAt->startOfDay()->diffInDays($expiresAt->copy()->startOfDay()))
            : 365;
        $elapsedDays = $joinedAt ? max(0, $joinedAt->startOfDay()->diffInDays(now()->startOfDay())) : 0;
        $progressPercent = min(100, max(0, (int) round(($elapsedDays / $totalDays) * 100)));

        $paymentHistory = $member
            ? $member->transactions()
                ->where('type', Transaction::TYPE_MEMBERSHIP)
                ->latest('transaction_at')
                ->limit(5)
                ->get()
            : collect();

        $latestPayment = $paymentHistory->first();

        return view('member.membership', [
            'user'             => $user,
            'member'           => $member,
            'membershipStatus' => $membershipStatus,
            'remainingDays'    => $remainingDays,
            'totalDays'        => $totalDays,
            'progressPercent'  => $progressPercent,
            'paymentHistory'   => $paymentHistory,
            'latestPayment'    => $latestPayment,
        ]);
    }

    /**
     * Display member profile details.
     */
    public function profile(Request $request): View|RedirectResponse
    {
        $session = $this->getActiveMemberSession($request);
        if (isset($session['redirect'])) {
            return $session['redirect'];
        }

        $user = $session['user'];
        $member = $session['member'];
        $totalCheckins = $member ? $member->verifiedCheckins()->count() : 0;
        $membershipStatus = 'member';

        return view('member.profile', [
            'user'                   => $user,
            'member'                 => $member,
            'totalCheckins'          => $totalCheckins,
            'membershipStatus'       => $membershipStatus,
            'photoChangesRemaining'  => max(0, 3 - (int) ($member?->profile_photo_change_count ?? 0)),
            'pendingPhotoRequest'    => $member
                ? ProfilePhotoChangeRequest::query()
                    ->where('member_id', $member->id)
                    ->where('status', 'pending')
                    ->latest()
                    ->first()
                : null,
        ]);
    }

    /**
     * Update member profile information.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $session = $this->getActiveMemberSession($request);
        if (isset($session['redirect'])) {
            return $session['redirect'];
        }

        $user = $session['user'];
        $member = $session['member'];

        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'phone'         => ['nullable', 'string', 'max:30'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($user) {
            $user->email = $validated['email'];
            $user->save();
        }

        if ($member) {
            $member->email = $validated['email'];
            $member->phone = $validated['phone'] ?? null;

            if ($request->hasFile('profile_photo')) {
                $changeCount = (int) ($member->profile_photo_change_count ?? 0);

                if ($changeCount < 3) {
                    if ($member->profile_photo_path) {
                        Storage::disk('public')->delete($member->profile_photo_path);
                    }

                    $member->profile_photo_path = $request->file('profile_photo')->store('member-profile-photos', 'public');
                    $member->profile_photo_change_count = $changeCount + 1;
                } else {
                    $hasPendingRequest = ProfilePhotoChangeRequest::query()
                        ->where('member_id', $member->id)
                        ->where('status', 'pending')
                        ->exists();

                    if ($hasPendingRequest) {
                        return redirect()
                            ->route('member.profile')
                            ->withErrors(['profile_photo' => 'Permintaan ganti foto profil Anda masih menunggu persetujuan admin.']);
                    }

                    $requestPath = $request->file('profile_photo')->store('member-profile-photo-requests', 'public');

                    ProfilePhotoChangeRequest::query()->create([
                        'user_id'              => $user?->id,
                        'member_id'            => $member->id,
                        'requested_photo_path' => $requestPath,
                        'status'               => 'pending',
                    ]);

                    $member->save();
                    $request->session()->put('auth.email', $validated['email']);

                    return redirect()
                        ->route('member.profile')
                        ->with('status', 'Jatah ganti foto profil sudah habis. Foto baru dikirim ke admin untuk persetujuan.');
                }
            }

            $member->save();
        }

        $request->session()->put('auth.email', $validated['email']);

        return redirect()->route('member.profile')->with('status', 'Profil berhasil diperbarui.');
    }

    /**
     * Dedicated route for updating member photo.
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $session = $this->getActiveMemberSession($request);
        if (isset($session['redirect'])) {
            return $session['redirect'];
        }

        $validated = $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = $session['user'];
        $member = $session['member'];

        if (! $member) {
            return redirect()->route('member.profile')->withErrors(['profile_photo' => 'Data member tidak ditemukan.']);
        }

        $changeCount = (int) ($member->profile_photo_change_count ?? 0);
        if ($changeCount < 3) {
            if ($member->profile_photo_path) {
                Storage::disk('public')->delete($member->profile_photo_path);
            }

            $member->profile_photo_path = $request->file('profile_photo')->store('member-profile-photos', 'public');
            $member->profile_photo_change_count = $changeCount + 1;
            $member->save();

            return redirect()->route('member.profile')->with('status', 'Foto profil berhasil diganti. Sisa ganti langsung: ' . max(0, 3 - ($changeCount + 1)) . 'x.');
        }

        $hasPendingRequest = ProfilePhotoChangeRequest::query()
            ->where('member_id', $member->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPendingRequest) {
            return redirect()->route('member.profile')->withErrors(['profile_photo' => 'Permintaan ganti foto profil Anda masih menunggu persetujuan admin.']);
        }

        ProfilePhotoChangeRequest::query()->create([
            'user_id'              => $user?->id,
            'member_id'            => $member->id,
            'requested_photo_path' => $request->file('profile_photo')->store('member-profile-photo-requests', 'public'),
            'status'               => 'pending',
        ]);

        return redirect()->route('member.profile')->with('status', 'Jatah ganti foto profil sudah habis. Foto baru dikirim ke admin untuk persetujuan.');
    }

    /**
     * Submit member feedback.
     */
    public function submitFeedback(Request $request): RedirectResponse
    {
        $session = $this->getActiveMemberSession($request);
        if (isset($session['redirect'])) {
            return $session['redirect'];
        }

        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'message'  => ['required', 'string', 'min:5', 'max:2000'],
        ]);

        $user = $session['user'];
        $member = $session['member'];

        MemberFeedback::query()->create([
            'user_id'   => $user->id,
            'member_id' => $member?->id,
            'name'      => $member?->full_name ?? $user->name,
            'email'     => $member?->email ?? $user->email,
            'subject'   => $validated['category'],
            'message'   => $validated['message'],
            'read_at'   => null,
        ]);

        return back()->with('status', 'Kritik & saran berhasil dikirim ke owner.');
    }

    /**
     * Member logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('auth');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('member.login');
    }

    /**
     * Serve profile photo from storage safely.
     */
    public function showProfilePhoto(string $path): BinaryFileResponse
    {
        $relativePath = str_replace(['..', '\\'], '', $path);
        $rootStoragePath = storage_path('app/public/' . $relativePath);

        abort_unless(is_file($rootStoragePath), 404);

        return response()->file($rootStoragePath);
    }
}
