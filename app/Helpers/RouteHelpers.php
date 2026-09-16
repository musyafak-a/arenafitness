<?php

namespace App\Helpers;

use App\Models\Checkin;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

/**
 * Kumpulan helper function yang dipakai bersama-sama di seluruh route
 * admin maupun cashier. Semua method bersifat static agar mudah dipanggil
 * tanpa perlu instantiasi.
 */
class RouteHelpers
{
    // ─── Auth ────────────────────────────────────────────────────────────────

    public static function redirectByRole(): \Illuminate\Http\RedirectResponse
    {
        return match (session('auth.role')) {
            'admin'        => redirect()->route('admin.dashboard'),
            'cashier'      => redirect()->route('cashier.dashboard'),
            'master_admin' => redirect()->route('admin.dashboard'),
            'member'       => redirect()->route('member.dashboard'),
            default        => redirect()->route('login'),
        };
    }

    public static function ensureAdmin(): ?\Illuminate\Http\RedirectResponse
    {
        if (! in_array(session('auth.role'), ['admin', 'master_admin'], true)) {
            return redirect()->route('admin.login');
        }

        return null;
    }

    public static function ensureCashier(): ?\Illuminate\Http\RedirectResponse
    {
        if (! in_array(session('auth.role'), ['cashier', 'master_admin'], true)) {
            return redirect()->route('cashier.login');
        }

        return null;
    }

    public static function ensureMember(): ?\Illuminate\Http\RedirectResponse
    {
        if (session('auth.role') !== 'member') {
            return redirect()->route('member.login');
        }

        return null;
    }

    /**
     * ID user yang sedang login (staff maupun member).
     */
    public static function authUserId(): ?int
    {
        $id = session('auth.user_id') ?? session('auth.id');

        return $id ? (int) $id : null;
    }

    // ─── Formatting ──────────────────────────────────────────────────────────

    public static function formatCurrency(?int $amount): string
    {
        return 'Rp' . number_format((int) $amount, 0, ',', '.');
    }

    public static function generateInvoice(string $prefix): string
    {
        return strtoupper($prefix) . '-' . now()->format('YmdHis') . random_int(100, 999);
    }

    // ─── Membership expiry ───────────────────────────────────────────────────

    /**
     * Hitung tanggal kadaluarsa 1 bulan dari $baseDate (atau hari ini).
     */
    public static function calculateMonthlyExpiryDate(?Carbon $baseDate): string
    {
        return ($baseDate ?? Carbon::today())
            ->copy()
            ->startOfDay()
            ->addMonthNoOverflow()
            ->toDateString();
    }

    /**
     * Hitung tanggal perpanjangan membership:
     * - Jika membership masih aktif → perpanjang dari tanggal expire.
     * - Jika sudah expire / belum punya → perpanjang dari hari ini.
     */
    public static function calculateMembershipRenewalExpiry(Member $member, ?Carbon $today = null): string
    {
        $today = ($today ?? Carbon::today())->copy()->startOfDay();

        if ($member->expires_at && $member->expires_at->copy()->startOfDay()->gte($today)) {
            return self::calculateMonthlyExpiryDate($member->expires_at->copy());
        }

        return self::calculateMonthlyExpiryDate($today);
    }

    // ─── Check-in code ───────────────────────────────────────────────────────

    public static function generateMemberCheckinCode(): string
    {
        do {
            $code = 'AGM-' . strtoupper(Str::random(10));
        } while (Member::query()->where('checkin_code', $code)->exists());

        return $code;
    }

    // ─── Member lifecycle status ──────────────────────────────────────────────

    public static function memberLifecycleStatus(?Member $member): array
    {
        $today           = Carbon::today();
        $sevenDaysFromNow = $today->copy()->addDays(7);
        $expiresAt      = $member?->expires_at;

        if (! $expiresAt) {
            return ['key' => 'unknown', 'label' => 'Tidak diketahui', 'color' => 'secondary'];
        }

        if ($expiresAt->lt($today)) {
            return ['key' => 'expired', 'label' => 'Expired', 'color' => 'danger'];
        }

        if ($expiresAt->betweenIncluded($today, $sevenDaysFromNow)) {
            return ['key' => 'expiring', 'label' => 'Akan Expired', 'color' => 'warning'];
        }

        return ['key' => 'active', 'label' => 'Aktif', 'color' => 'success'];
    }

    // ─── Store check-in (dipakai admin, kasir, & QR self-service) ────────────

    public static function storeMemberCheckin(
        Request $request,
        string $actor = 'admin',
        string $redirectRoute = 'admin.checkins',
        array $redirectParams = []
    ): \Illuminate\Http\RedirectResponse {
        $validated = $request->validate([
            'member_id'      => ['nullable', 'exists:members,id', 'required_without_all:checkin_code,submitted_phone'],
            'checkin_code'   => ['nullable', 'string', 'max:40', 'required_without_all:member_id,submitted_phone'],
            'submitted_name' => ['nullable', 'string', 'max:255'],
            'submitted_phone'=> ['nullable', 'string', 'max:30', 'required_without_all:member_id,checkin_code'],
            'checkin_date'   => ['nullable', 'date'],
            'checkin_time'   => ['nullable', 'date_format:H:i'],
            'notes'          => ['nullable', 'string'],
        ]);

        $resolvedCheckinCode = strtoupper(trim((string) ($validated['checkin_code'] ?? '')));
        $resolvedPhone       = preg_replace('/\D+/', '', (string) ($validated['submitted_phone'] ?? ''));

        $member = null;

        if (! empty($validated['member_id'])) {
            $member = Member::query()->find($validated['member_id']);
        } elseif (! empty($resolvedCheckinCode)) {
            $member = Member::query()->where('checkin_code', $resolvedCheckinCode)->first();
        } elseif ($actor === 'qr_member' && ! empty($resolvedPhone)) {
            $member = Member::query()
                ->whereNotNull('phone')
                ->get()
                ->first(fn (Member $candidate) => preg_replace('/\D+/', '', (string) $candidate->phone) === $resolvedPhone);
        }

        $errorKey = ! empty($resolvedCheckinCode)
            ? 'checkin_code'
            : (! empty($validated['member_id']) ? 'member_id' : 'submitted_phone');

        if (! $member) {
            return redirect()->route($redirectRoute, $redirectParams)
                ->withErrors([$errorKey => 'Member untuk check-in tidak ditemukan.'])
                ->withInput();
        }

        if (! $member->expires_at || $member->expires_at->lt(Carbon::today())) {
            return redirect()->route($redirectRoute, $redirectParams)
                ->withErrors([$errorKey => 'Membership member ini sudah expired dan tidak bisa check-in.'])
                ->withInput();
        }

        $isQrMemberCheckin = $actor === 'qr_member';
        // Scan QR di kasir langsung dianggap valid, tapi QR self-service (member-checkin) masuk sebagai pending.
        $verificationStatus = ($isQrMemberCheckin && $redirectRoute === 'member.checkin') ? 'pending' : 'verified';

        if ($isQrMemberCheckin && empty($resolvedCheckinCode)) {
            $request->validate([
                'submitted_name'  => ['required', 'string', 'max:255'],
                'submitted_phone' => ['required', 'string', 'max:30'],
            ]);
        }

        $successLabel = match ($actor) {
            'cashier'    => 'kasir',
            'qr_member'  => 'QR',
            default      => 'admin',
        };

        $checkin = Checkin::create([
            'member_id'           => $member->id,
            'checked_in_at'       => (! empty($validated['checkin_date']) && ! empty($validated['checkin_time']))
                ? Carbon::parse($validated['checkin_date'] . ' ' . $validated['checkin_time'])
                : now(),
            'checkin_method'      => $actor,
            'verification_status' => $verificationStatus,
            'submitted_name'      => $validated['submitted_name'] ?? null,
            'submitted_phone'     => $validated['submitted_phone'] ?? null,
            'verified_at'         => $verificationStatus === 'verified' ? now() : null,
            'verified_by'         => $verificationStatus === 'verified' ? self::authUserId() : null,
            'notes'               => $validated['notes'] ?? null,
        ]);

        $redirect = redirect()->route($redirectRoute, $redirectParams)
            ->with('status', "Check-in {$successLabel} untuk {$member->full_name} berhasil dicatat.")
            ->with('welcome_name', $member->full_name);

        return $redirect;
    }

    // ─── Page meta ───────────────────────────────────────────────────────────

    public static function pageMeta(string $key): array
    {
        $map = [
            'dashboard' => [
                'pageTitle'          => 'Dashboard Admin Arena Gym',
                'activePage'         => 'dashboard',
                'sidebarStatusTitle' => 'Operasional Stabil',
                'sidebarStatusNote'  => 'Pantau semua modul admin dari halaman utama.',
                'icon'               => 'layout-dashboard',
                'heroImage'          => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&q=80&w=1200',
            ],
            'members' => [
                'pageTitle'          => 'Member - Arena Gym',
                'activePage'         => 'members',
                'sidebarStatusTitle' => 'Data Member',
                'sidebarStatusNote'  => 'Fokus pada member dengan paket aktif atau expired.',
                'icon'               => 'users',
                'heroImage'          => 'https://images.unsplash.com/photo-1571012281386-7a0c1645b252?auto=format&fit=crop&q=80&w=1200',
            ],
            'daily-passes' => [
                'pageTitle'          => 'Daily Pass - Arena Gym',
                'activePage'         => 'daily-passes',
                'sidebarStatusTitle' => 'Data Daily Pass',
                'sidebarStatusNote'  => 'Pantau kunjungan daily pass dan pembayaran harian.',
                'icon'               => 'user-plus',
                'heroImage'          => 'https://images.unsplash.com/photo-1540497077202-7c8a3999166f?auto=format&fit=crop&q=80&w=1200',
            ],
            'products' => [
                'pageTitle'          => 'Produk - Arena Gym',
                'activePage'         => 'products',
                'sidebarStatusTitle' => 'Master Produk',
                'sidebarStatusNote'  => 'Kelola suplemen dan vitamin untuk kebutuhan penjualan kasir.',
                'icon'               => 'package',
                'heroImage'          => 'https://images.unsplash.com/photo-1583454110333-a006bb6d9894?auto=format&fit=crop&q=80&w=1200',
            ],
            'checkins' => [
                'pageTitle'          => 'Check-in - Arena Gym',
                'activePage'         => 'checkins',
                'sidebarStatusTitle' => 'Check-in Aktif',
                'sidebarStatusNote'  => 'Aktivitas kunjungan harian terus diperbarui.',
                'icon'               => 'clipboard-check',
                'heroImage'          => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&q=80&w=1200',
            ],
            'announcements' => [
                'pageTitle'          => 'Pengumuman - Arena Gym',
                'activePage'         => 'announcements',
                'sidebarStatusTitle' => 'Info Aktif',
                'sidebarStatusNote'  => 'Pengumuman penting siap dipublikasikan ke member.',
                'icon'               => 'megaphone',
                'heroImage'          => 'https://images.unsplash.com/photo-1483721310020-03333e577078?auto=format&fit=crop&q=80&w=1200',
            ],
            'reports' => [
                'pageTitle'          => 'Laporan - Arena Gym',
                'activePage'         => 'reports',
                'sidebarStatusTitle' => 'Laporan Siap',
                'sidebarStatusNote'  => 'Ringkasan harian dapat diakses dan diunduh.',
                'icon'               => 'bar-chart-3',
                'heroImage'          => 'https://images.unsplash.com/photo-1554224158-b67a1c00983f?auto=format&fit=crop&q=80&w=1200',
            ],
        ];

        return $map[$key] ?? [];
    }

    // ─── Cashier view data builder ────────────────────────────────────────────

    public static function buildCashierViewData(array $overrides = []): array
    {
        $today = Carbon::today();

        $transactions = Transaction::query()
            ->with(['member', 'dailyGuest', 'items.product', 'cashier'])
            ->latest('transaction_at')
            ->get();

        $todayCheckins = Checkin::query()
            ->with('member')
            ->whereDate('checked_in_at', $today)
            ->where('verification_status', 'verified')
            ->latest('checked_in_at')
            ->get();

        $pendingCheckins = Checkin::query()
            ->with('member')
            ->where('verification_status', 'pending')
            ->latest('checked_in_at')
            ->get();

        $memberPayments = $transactions->where('type', Transaction::TYPE_MEMBERSHIP)->values();
        $dailyPayments  = $transactions->where('type', Transaction::TYPE_DAILY_PASS)->values();
        $productPayments = $transactions->where('type', Transaction::TYPE_PRODUCT_SALE)->values();
        $todayMemberPayments = $memberPayments
            ->filter(fn (Transaction $t) => $t->transaction_at?->isToday())
            ->values();
        $todayDailyPayments = $dailyPayments
            ->filter(fn (Transaction $t) => $t->transaction_at?->isToday())
            ->values();

        $verifiedTransactions     = $transactions->filter(fn (Transaction $t) => $t->payment_status === 'verified')->values();
        $todayTransactions        = $transactions->filter(fn (Transaction $t) => $t->transaction_at?->isToday())->values();
        $todayVerifiedTransactions = $todayTransactions->filter(fn (Transaction $t) => $t->payment_status === 'verified')->values();
        $todayPendingTransactions  = $todayTransactions->filter(fn (Transaction $t) => $t->payment_status !== 'verified')->values();
        $todayRevenue              = $todayVerifiedTransactions->sum('amount');

        $paymentMethodSummary = collect(['cash', 'qris', 'transfer'])
            ->map(function (string $method) use ($todayVerifiedTransactions) {
                $amount = $todayVerifiedTransactions->where('payment_method', $method)->sum('amount');

                return [
                    'label'  => strtoupper($method),
                    'value'  => self::formatCurrency($amount),
                    'amount' => $amount,
                ];
            })
            ->filter(fn (array $item) => $item['amount'] > 0)
            ->values();

        $paymentMethodTotal = max($paymentMethodSummary->sum('amount'), 1);

        $paymentMethods = $paymentMethodSummary
            ->map(fn (array $item) => [
                'label'    => $item['label'],
                'value'    => $item['value'],
                'progress' => (int) round(($item['amount'] / $paymentMethodTotal) * 100),
                'color'    => 'danger',
            ])
            ->values()
            ->all();

        return array_merge([
            'pageTitle'           => 'Dashboard Kasir Arena Gym',
            'activePage'          => 'cashier.dashboard',
            'sidebarStatusTitle'  => 'Shift Kasir Aktif',
            'sidebarStatusNote'   => 'Pantau pembayaran, transaksi, dan bukti pembayaran harian.',
            'cashierShift'        => ['start' => '08:00', 'end' => '16:00', 'label' => '08:00 - 16:00'],
            'cashierCheckinMembers' => Member::query()
                ->whereDate('expires_at', '>=', $today)
                ->orderBy('full_name')
                ->get(),
            'cashierTodayCheckins'   => $todayCheckins,
            'cashierPendingCheckins' => $pendingCheckins,
            'cashierLatestCheckin'   => Checkin::query()
                ->with('member')
                ->where('verification_status', 'verified')
                ->latest('checked_in_at')
                ->first(),
            'cashierStats' => [
                ['label' => 'Pembayaran Member',        'value' => number_format($todayMemberPayments->count(), 0, ',', '.'), 'change' => $todayMemberPayments->where('payment_status', 'verified')->count() . ' terverifikasi'],
                ['label' => 'Pembayaran Daily Pass',    'value' => number_format($todayDailyPayments->count(), 0, ',', '.'),  'change' => $todayDailyPayments->where('payment_status', 'verified')->count() . ' terverifikasi'],
                ['label' => 'Total Transaksi Hari Ini', 'value' => number_format($todayTransactions->count(), 0, ',', '.'), 'change' => $todayPendingTransactions->count() . ' pending'],
                ['label' => 'Pendapatan Hari Ini',      'value' => self::formatCurrency($todayRevenue), 'change' => $todayVerifiedTransactions->count() . ' transaksi lunas'],
            ],
            'transactions'   => $transactions,
            'memberPayments' => $memberPayments,
            'dailyPayments'  => $dailyPayments,
            'productPayments'=> $productPayments,
            'receiptQueue'   => $transactions->values(),
            'paymentMethods' => $paymentMethods,
        ], $overrides);
    }
}