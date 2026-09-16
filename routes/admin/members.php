<?php

use App\Helpers\RouteHelpers;
use App\Models\Checkin;
use App\Models\Member;
use App\Models\MembershipPlan;
use App\Models\MembershipSubscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Admin – Manajemen Member
|--------------------------------------------------------------------------
*/

// Rules untuk validasi agar konsisten
$memberValidationRules = function (?Member $member = null): array {
    return [
        'full_name'      => ['required', 'string', 'max:255'],
        'email'          => ['nullable', 'email', 'max:255', 'unique:members,email,' . ($member->id ?? 'NULL')],
        'phone'          => ['nullable', 'string', 'max:30'],
        'profile_photo'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        'joined_at'      => ['nullable', 'date'],
        'payment_method' => ['required', 'string'],
        'duration'       => ['nullable', 'integer', 'min:1'],
    ];
};

// ── Index (Updated with Pagination) ──────────────────────────────────────────
Route::get('/members', function (Request $request) {
    if ($redirect = RouteHelpers::ensureAdmin()) return $redirect;

    $today         = Carbon::today();
    $memberSection = $request->string('section', 'active')->lower()->value();
    $memberSearch  = trim($request->string('q')->value());

    $baseQuery = Member::query()
        ->withCount([
            'verifiedCheckins as checkins_count',
            'productTransactions as product_transactions_count',
        ])
        ->latest();

    if ($memberSearch !== '') {
        $baseQuery->where(function ($q) use ($memberSearch) {
            $q->where('full_name', 'like', '%' . $memberSearch . '%')
                ->orWhere('email', 'like', '%' . $memberSearch . '%')
                ->orWhere('phone', 'like', '%' . $memberSearch . '%')
                ->orWhere('checkin_code', 'like', '%' . $memberSearch . '%');
        });
    }

    // ── Counter (selalu dari seluruh data, tidak ikut section) ───────
    $totalActiveCount   = Member::where('expires_at', '>=', $today)->count();
    $totalExpiredCount  = Member::where('expires_at', '<', $today)->count();
    $totalMembersCount  = Member::count();

    // Expiring soon (7 hari ke depan) — dari seluruh data
    $expiringSoonCount  = Member::whereBetween('expires_at', [$today, $today->copy()->addDays(7)])->count();

    // ── Data untuk tabel (paginated, ikut search & section) ──────────
    if ($memberSection === 'expired') {
        $activeMembers  = (clone $baseQuery)->where('expires_at', '>=', $today)->paginate(8)->withQueryString();
        $expiredMembers = (clone $baseQuery)->where('expires_at', '<', $today)->paginate(8)->withQueryString();
        $currentItems   = $expiredMembers;
    } else {
        $activeMembers  = (clone $baseQuery)->where('expires_at', '>=', $today)->paginate(8)->withQueryString();
        $expiredMembers = (clone $baseQuery)->where('expires_at', '<', $today)->paginate(8)->withQueryString();
        $currentItems   = $activeMembers;
    }

    $currentItems->getCollection()->load([
        'verifiedCheckins',
        'productTransactions.items',
    ]);

    return view('admin.members', array_merge(RouteHelpers::pageMeta('members'), [
        'memberSection'      => $memberSection,
        'memberSearch'       => $memberSearch,
        'activeMembers'      => $activeMembers,
        'expiredMembers'     => $expiredMembers,
        'currentItems'       => $currentItems,

        // Counter cards — selalu total keseluruhan
        'totalActiveCount'   => $totalActiveCount,
        'totalExpiredCount'  => $totalExpiredCount,
        'totalMembersCount'  => $totalMembersCount,
        'expiringSoonCount'  => $expiringSoonCount,
    ]));
})->name('members');

// ── Store ─────────────────────────────────────────────────────────────────────
Route::post('/members', function (Request $request) {
    if ($redirect = RouteHelpers::ensureAdmin()) return $redirect;

    $validated = $request->validate([
        'full_name'      => 'required|string|max:255',
        'email'          => 'required|email|unique:members,email|unique:users,email',
        'phone'          => 'nullable|string|max:20',
        'joined_at'      => 'required|date',
        'payment_method' => 'required|string',
        'notes'          => 'nullable|string',
        'profile_photo'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    $joinedAt = Carbon::parse($validated['joined_at']);
    $expiresAt = $joinedAt->copy()->addMonthNoOverflow();

    $baseLogin = Str::slug($validated['full_name'], '.');
    $login = $baseLogin;
    $counter = 1;
    while (User::where('login', $login)->exists()) {
        $login = $baseLogin . $counter;
        $counter++;
    }

    // Create User record first
    $user = User::create([
        'name'     => $validated['full_name'],
        'email'    => $validated['email'],
        'login'    => $login, // Use generated username
        'role'     => 'member',
        'password' => bcrypt('member123'), // Default password
    ]);

    $memberData = [
        'user_id'        => $user->id,
        'full_name'      => $validated['full_name'],
        'email'          => $validated['email'] ?? null,
        'phone'          => $validated['phone'] ?? null,
        'joined_at'      => $joinedAt,
        'expires_at'     => $expiresAt,
        'checkin_code'   => 'AGM-' . strtoupper(Str::random(8)),
        'notes'          => $validated['notes'] ?? null,
    ];

    if ($request->hasFile('profile_photo')) {
        $memberData['profile_photo_path'] = $request->file('profile_photo')->store('member-photos', 'public');
    }

    $member = Member::create($memberData);

    // Get 1 Month plan
    $plan = MembershipPlan::where('duration_months', 1)->first();

    // Create Subscription record
    MembershipSubscription::create([
        'member_id'          => $member->id,
        'membership_plan_id' => $plan?->id,
        'start_date'         => $joinedAt,
        'end_date'           => $expiresAt,
        'amount_paid'        => $plan?->price ?? 90000,
        'payment_method'     => $validated['payment_method'],
        'status'             => 'active',
    ]);

    // Create Transaction record
    Transaction::create([
        'invoice'            => 'INV-' . date('Ymd') . strtoupper(Str::random(6)),
        'member_id'          => $member->id,
        'cashier_user_id'    => auth()->id(),
        'type'               => Transaction::TYPE_MEMBERSHIP,
        'customer_name'      => $member->full_name,
        'description'        => 'Pendaftaran Member Baru (1 Bulan)',
        'amount'             => $plan?->price ?? 90000,
        'paid_amount'        => $plan?->price ?? 90000,
        'change_amount'      => 0,
        'payment_method'     => $validated['payment_method'],
        'payment_status'     => 'verified',
        'transaction_at'     => now(),
        'notes'              => $validated['notes'] ?? 'Registrasi member via Admin',
    ]);

    return redirect()->route('admin.members')->with('status', "Member berhasil ditambahkan! Username: {$login} | Password default: member123");
})->name('members.store');

// ── Update & Perpanjang ────────────────────────────────────────────────────────
Route::put('/members/{member}', function (Request $request, Member $member) use ($memberValidationRules) {
    if ($redirect = RouteHelpers::ensureAdmin()) return $redirect;

    $validated = $request->validate($memberValidationRules($member));

    $data = [
        'full_name'      => $validated['full_name'],
        'email'          => $validated['email'] ?? $member->email,
        'phone'          => $validated['phone'] ?? $member->phone,
        'joined_at'      => $validated['joined_at'] ?? $member->joined_at,
    ];

    if ($request->filled('duration')) {
        $months = (int) $request->duration;
        $baseDate = ($member->expires_at && Carbon::parse($member->expires_at)->isFuture())
            ? Carbon::parse($member->expires_at)
            : now();

        $startDate = $baseDate->copy();
        $newExpiresAt = $baseDate->copy()->addMonths($months);
        $data['expires_at'] = $newExpiresAt;

        $plan = MembershipPlan::where('duration_months', $months)->first();
        $amount = $plan ? $plan->price : ($months * 90000);

        MembershipSubscription::create([
            'member_id'          => $member->id,
            'membership_plan_id' => $plan?->id,
            'start_date'         => $startDate,
            'end_date'           => $newExpiresAt,
            'amount_paid'        => $amount,
            'payment_method'     => $validated['payment_method'],
            'status'             => 'active',
        ]);

        Transaction::create([
            'invoice'            => 'INV-' . date('Ymd') . strtoupper(Str::random(6)),
            'member_id'          => $member->id,
            'cashier_user_id'    => auth()->id(),
            'customer_name'      => $data['full_name'],
            'type'               => Transaction::TYPE_MEMBERSHIP,
            'description'        => "Perpanjangan Membership ($months Bulan)",
            'amount'             => $amount,
            'paid_amount'        => $amount,
            'change_amount'      => 0,
            'payment_method'     => $validated['payment_method'],
            'payment_status'     => 'verified',
            'transaction_at'     => now(),
            'notes'              => "Perpanjangan member oleh Admin: $months Bulan",
        ]);
    }

    if ($request->hasFile('profile_photo')) {
        if ($member->profile_photo_path) Storage::disk('public')->delete($member->profile_photo_path);
        $data['profile_photo_path'] = $request->file('profile_photo')->store('member-photos', 'public');
    }

    $member->update($data);

    // Also update associated user name/email if user exists
    if ($member->user) {
        $member->user->update([
            'name'  => $data['full_name'],
            'email' => $data['email'],
        ]);
    }

    return redirect()->route('admin.members')->with('status', 'Data member dan masa aktif berhasil diperbarui.');
})->name('members.update');

// ── Destroy ───────────────────────────────────────────────────────────────────
Route::delete('/members/{member}', function (Member $member) {
    if ($redirect = RouteHelpers::ensureAdmin()) return $redirect;

    if ($member->profile_photo_path) {
        Storage::disk('public')->delete($member->profile_photo_path);
    }

    if ($member->user) {
        $member->user->delete();
    }

    $member->delete();
    return redirect()->route('admin.members')->with('status', 'Member berhasil dihapus.');
})->name('members.destroy');
