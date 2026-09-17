<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RouteHelpers;
use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\DailyGuest;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckinController extends Controller
{
    /**
     * Display check-in hub for members and daily passes.
     */
    public function index(Request $request): View|RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $startOfToday = now()->startOfDay();
        $endOfToday = now()->endOfDay();

        $todayCheckinsCount = Checkin::where('verification_status', 'verified')
            ->whereBetween('checked_in_at', [$startOfToday, $endOfToday])
            ->count();

        $todayDailyPassCount = DailyGuest::whereBetween('created_at', [$startOfToday, $endOfToday])
            ->count();

        $todayDailyPassRevenue = Transaction::where('type', Transaction::TYPE_DAILY_PASS)
            ->where('payment_status', 'verified')
            ->whereBetween('transaction_at', [$startOfToday, $endOfToday])
            ->sum('amount');

        $dateFrom = $request->filled('date_from')
            ? Carbon::parse($request->date_from)->startOfDay()
            : null;

        $dateTo = $request->filled('date_to')
            ? Carbon::parse($request->date_to)->endOfDay()
            : null;

        $typeFilter = $request->input('type'); // member | daily_pass | null

        $memberLogs = collect();
        if (!$typeFilter || $typeFilter === 'member') {
            $memberQuery = Checkin::with('member')
                ->whereNotNull('member_id')
                ->where('verification_status', 'verified')
                ->latest('checked_in_at');

            if ($dateFrom) {
                $memberQuery->where('checked_in_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $memberQuery->where('checked_in_at', '<=', $dateTo);
            }

            $memberLogs = $memberQuery->get()->map(fn ($item) => [
                'type' => 'member',
                'nama' => $item->member->full_name ?? 'N/A',
                'sub' => $item->member->checkin_code ?? '',
                'info' => 'Aktif hingga: ' . ($item->member->expires_at?->format('d M Y') ?? '-'),
                'waktu' => $item->checked_in_at ? $item->checked_in_at->translatedFormat('d M Y, H:i') : '-',
                'waktu_raw' => $item->checked_in_at,
                'payment_method' => null,
                'amount' => null,
            ]);
        }

        $dailyPassLogs = collect();
        if (!$typeFilter || $typeFilter === 'daily_pass') {
            $dailyPassQuery = DailyGuest::latest();

            if ($dateFrom) {
                $dailyPassQuery->where('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $dailyPassQuery->where('created_at', '<=', $dateTo);
            }

            $dailyPassLogs = $dailyPassQuery->get()->map(fn ($item) => [
                'type' => 'daily_pass',
                'nama' => $item->full_name,
                'sub' => '',
                'info' => 'Daily Pass',
                'waktu' => $item->created_at ? $item->created_at->translatedFormat('d M Y, H:i') : '-',
                'waktu_raw' => $item->created_at,
                'payment_method' => $item->payment_method ?? 'Cash',
                'amount' => $item->payment_amount ?? 20000,
            ]);
        }

        $merged = $memberLogs->concat($dailyPassLogs)->sortByDesc('waktu_raw')->values();
        $perPage = 10;
        $currentPage = (int) $request->input('page', 1);

        $allLogs = new LengthAwarePaginator(
            $merged->forPage($currentPage, $perPage),
            $merged->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        $memberOptions = Member::where('expires_at', '>=', now())
            ->orderBy('full_name')
            ->get(['id', 'full_name', 'checkin_code']);

        $paymentMethods = ['Cash', 'Transfer Bank', 'QRIS', 'Debit Card'];

        return view('admin.checkins', array_merge(RouteHelpers::pageMeta('checkins'), [
            'todayCheckinsCount' => $todayCheckinsCount,
            'todayDailyPassCount' => $todayDailyPassCount,
            'todayDailyPassRevenue' => $todayDailyPassRevenue,
            'allLogs' => $allLogs,
            'memberOptions' => $memberOptions,
            'paymentMethods' => $paymentMethods,
            'checkinRecords' => Checkin::with('member')
                ->whereNotNull('member_id')
                ->where('verification_status', 'verified')
                ->whereBetween('checked_in_at', [$startOfToday, $endOfToday])
                ->latest('checked_in_at')
                ->get(),
            'dailyPassEntries' => DailyGuest::whereBetween('created_at', [$startOfToday, $endOfToday])
                ->latest()
                ->get(),
        ]));
    }

    /**
     * Store member checkin.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        return RouteHelpers::storeMemberCheckin(
            request: $request,
            actor: 'admin',
            redirectRoute: 'admin.checkins'
        );
    }

    /**
     * Store daily pass visit and checkin.
     */
    public function storeDailyPass(Request $request): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'price' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        $guest = DailyGuest::create([
            'full_name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'visit_type' => 'regular',
            'visit_at' => now(),
        ]);

        // Record checkin for guest
        Checkin::create([
            'daily_guest_id' => $guest->id,
            'checked_in_at' => now(),
            'checkin_method' => 'admin',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        // Record transaction
        Transaction::create([
            'invoice' => 'INV-' . date('Ymd') . strtoupper(Str::random(6)),
            'daily_guest_id' => $guest->id,
            'cashier_user_id' => auth()->id(),
            'customer_name' => $guest->full_name,
            'type' => Transaction::TYPE_DAILY_PASS,
            'description' => 'Daily Pass Kunjungan',
            'amount' => (int) $validated['price'],
            'paid_amount' => (int) $validated['price'],
            'change_amount' => 0,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'verified',
            'transaction_at' => now(),
        ]);

        return redirect()->back()->with('status', 'Daily pass berhasil dicatat!');
    }
}
