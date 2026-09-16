<?php

use App\Helpers\RouteHelpers;
use App\Models\Checkin;
use App\Models\DailyGuest;
use App\Models\ExpenseRecord;
use App\Models\Member;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Admin – Laporan & Ekspor
|--------------------------------------------------------------------------
*/

$buildAdminReportsData = function (Request $request): array {
    // Ambil data dasar
    $memberRecords       = Member::query()->get();
    $dailyPassRecords    = DailyGuest::query()->get();
    $checkinRecords      = Checkin::query()->with('member')->where('verification_status', 'verified')->latest('checked_in_at')->get();
    $cashierTransactions = Transaction::query()->with(['member', 'items.product'])->latest('transaction_at')->get();
    $expenseRecords      = ExpenseRecord::query()->latest('expense_date')->latest()->get();
    $vitaminProductRecords = Product::query()
        ->whereHas('category', fn($q) => $q->where('name', 'like', '%vitamin%'))
        ->orWhere('name', 'like', '%vitamin%')
        ->orderBy('name')
        ->get();
    $stockLogRecords = \App\Models\ProductStockLog::with(['product', 'user'])->latest()->get();

    $verifiedTransactions    = $cashierTransactions->filter(fn (Transaction $t) => $t->payment_status === 'verified');
    $memberPaymentRecords     = $cashierTransactions->where('type', Transaction::TYPE_MEMBERSHIP)->values();
    $dailyPassPaymentRecords  = $cashierTransactions->where('type', Transaction::TYPE_DAILY_PASS)->values();
    $productSaleRecords       = $cashierTransactions->where('type', Transaction::TYPE_PRODUCT_SALE)->values();
    $otherTransactionRecords  = $cashierTransactions->where('type', Transaction::TYPE_OTHER)->values();

    $activeMembers     = $memberRecords->filter(fn (Member $m) => $m->expires_at && $m->expires_at->gt(now()->addDays(7)));
    $endingSoonMembers = $memberRecords->filter(fn (Member $m) => $m->expires_at && $m->expires_at->lte(now()->addDays(7)) && $m->expires_at->gte(now()));
    $expiredMembers    = $memberRecords->filter(fn (Member $m) => $m->expires_at && $m->expires_at->lt(now()));

    $membershipReportSummary = [
        'active'  => $activeMembers->count(),
        'ending'  => $endingSoonMembers->count(),
        'expired' => $expiredMembers->count(),
    ];

    // ── Filter params ─────────────────────────────────────────────────────────
    $activeDetailTab = in_array($request->query('detail_tab'), ['activity', 'membership', 'member-payments', 'daily-pass-payments', 'other-transactions', 'expenses', 'stock-logs'], true) ? $request->query('detail_tab') : 'activity';
    $detailFilterType = in_array($request->query('detail_filter'), ['month', 'range'], true) ? $request->query('detail_filter') : 'month';
    $detailMonthInput = (string) $request->query('detail_month', now()->format('Y-m'));
    $detailMonth      = preg_match('/^\d{4}-\d{2}$/', $detailMonthInput) ? Carbon::createFromFormat('Y-m', $detailMonthInput)->startOfMonth() : now()->startOfMonth();

    $detailFrom = $request->filled('detail_from') ? Carbon::parse($request->query('detail_from'))->startOfDay() : $detailMonth->copy()->startOfMonth();
    $detailTo   = $request->filled('detail_to') ? Carbon::parse($request->query('detail_to'))->endOfDay() : $detailMonth->copy()->endOfMonth();

    if ($detailTo->lt($detailFrom)) {
        [$detailFrom, $detailTo] = [$detailTo->copy()->startOfDay(), $detailFrom->copy()->endOfDay()];
    }

    $detailRangeStart = $detailFilterType === 'range' ? $detailFrom->copy()->startOfDay() : $detailMonth->copy()->startOfMonth();
    $detailRangeEnd   = $detailFilterType === 'range' ? $detailTo->copy()->endOfDay()     : $detailMonth->copy()->endOfMonth();

    $filteredMemberRecords = $memberRecords;

    $isDateIncluded = function ($date) use ($detailFilterType, $detailFrom, $detailTo, $detailMonth): bool {
        if (! $date) return false;
        $date = $date instanceof Carbon ? $date : Carbon::parse($date);
        return $detailFilterType === 'range' ? $date->betweenIncluded($detailFrom, $detailTo) : $date->betweenIncluded($detailMonth->copy()->startOfMonth(), $detailMonth->copy()->endOfMonth());
    };

    $reportDateLabel = $detailFilterType === 'range' ? $detailFrom->format('d M Y') . ' - ' . $detailTo->format('d M Y') : $detailMonth->translatedFormat('F Y');

    // ── Financial summary ─────────────────────────────────────────────────────
    $reportMonthStart = $detailMonth->copy()->startOfMonth();
    $reportMonthEnd   = $detailMonth->copy()->endOfMonth();

    $monthlyVerifiedTransactions = $verifiedTransactions->filter(fn ($t) => $t->transaction_at && $t->transaction_at->between($reportMonthStart, $reportMonthEnd))->values();
    $monthlyExpenseRecords = $expenseRecords->filter(fn ($e) => $e->expense_date && $e->expense_date->between($reportMonthStart, $reportMonthEnd))->values();

    $financialSummary = [
        'month_label'               => $reportMonthStart->translatedFormat('F Y'),
        'total_revenue'             => $monthlyVerifiedTransactions->sum('amount'),
        'total_expense'             => $monthlyExpenseRecords->sum('amount'),
        'net_revenue'               => $monthlyVerifiedTransactions->sum('amount') - $monthlyExpenseRecords->sum('amount'),
        'member_revenue'            => $monthlyVerifiedTransactions->where('type', Transaction::TYPE_MEMBERSHIP)->sum('amount'),
        'daily_pass_revenue'        => $monthlyVerifiedTransactions->where('type', Transaction::TYPE_DAILY_PASS)->sum('amount'),
        'other_revenue'             => $monthlyVerifiedTransactions->where('type', Transaction::TYPE_OTHER)->sum('amount'),
        'verified_transaction_count'=> $monthlyVerifiedTransactions->count(),
        'expense_count'             => $monthlyExpenseRecords->count(),
    ];

    // ── Training Stats ────────────────────────────────────────────────────────
    $dailyTrainingStats = [
        'today_checkins' => $checkinRecords->filter(fn($c) => $c->checked_in_at && $c->checked_in_at->isToday())->count(),
    ];

    // ── Histori Bulanan ───────────────────────────────────────────────────────
    $financialMonthlyHistory = collect(range(0, 5))->map(function (int $offset) use ($verifiedTransactions, $expenseRecords) {
        $monthStart = now()->copy()->subMonths($offset)->startOfMonth();
        $monthEnd   = $monthStart->copy()->endOfMonth();
        $mRev = $verifiedTransactions->filter(fn ($t) => $t->transaction_at && $t->transaction_at->between($monthStart, $monthEnd))->sum('amount');
        $mExp = $expenseRecords->filter(fn ($e) => $e->expense_date && $e->expense_date->between($monthStart, $monthEnd))->sum('amount');
        return ['month_label' => $monthStart->translatedFormat('F Y'), 'total_revenue' => $mRev, 'total_expense' => $mExp, 'net_revenue' => $mRev - $mExp];
    })->values()->reverse()->values();

    // ── Visitor Statistics ────────────────────────────────────────────────────
    $visitorDailyRows = collect();
    for ($date = $detailRangeStart->copy(); $date->lte($detailRangeEnd); $date->addDay()) {
        $visitorDailyRows->push(['date' => $date->copy()]);
    }

    $buildDailyRows = function (Carbon $start, Carbon $end, callable $callback) {
        $rows = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $rows[] = $callback($date);
        }
        return $rows;
    };

    $buildMonthlyRows = function (int $year, callable $callback) {
        $rows = [];
        for ($month = 1; $month <= 12; $month++) {
            $rows[] = $callback(Carbon::createFromFormat('Y-m-d', sprintf('%04d-%02d-01', $year, $month)));
        }
        return $rows;
    };

    $formatMoney = fn(int $amount): string => 'Rp' . number_format($amount, 0, ',', '.');

    $reportCatalog = collect([
        [
            'title'       => 'Laporan Stok Barang',
            'group'       => 'Operasional',
            'summary'     => 'Riwayat penambahan, pengurangan, dan transaksi penjualan produk.',
            'date_label'  => 'Semua',
            'count_label' => $stockLogRecords->count() . ' log',
            'highlight'   => 'Stok terbaru',
            'preview'     => 'Data pergerakan stok',
            'details'     => ['Barang masuk', 'Barang keluar', 'Transaksi kasir'],
            'slug'        => 'laporan-stok',
        ],
        [
            'title'       => 'Laporan Member',
            'group'       => 'Operasional',
            'summary'     => 'Ringkasan keanggotaan member, status aktif, member baru, dan expired.',
            'date_label'  => 'Per ' . now()->format('d M Y'),
            'count_label' => $memberRecords->count() . ' member',
            'highlight'   => $membershipReportSummary['ending'] . ' perlu follow-up',
            'preview'     => 'Data membership lengkap',
            'details'     => ['Jumlah member aktif', 'Member baru', 'Member expired', 'Riwayat pendaftaran', 'Data paket member'],
        ],
        [
            'title'       => 'Laporan Keuangan',
            'group'       => 'Keuangan',
            'summary'     => 'Ringkasan pemasukan, pembayaran member, dan riwayat transaksi.',
            'date_label'  => $financialSummary['month_label'],
            'count_label' => $financialSummary['verified_transaction_count'] . ' transaksi',
            'highlight'   => 'Rp' . number_format($financialSummary['net_revenue'], 0, ',', '.'),
            'preview'     => 'Pendapatan dan pembayaran',
            'details'     => ['Total pemasukan', 'Pembayaran member', 'Riwayat pemasukan', 'Riwayat pengeluaran'],
        ],
        [
            'title'       => 'Laporan Kehadiran',
            'group'       => 'Operasional',
            'summary'     => 'Rekap check-in member untuk analisis kehadiran.',
            'date_label'  => $reportDateLabel,
            'count_label' => $checkinRecords->count() . ' check-in',
            'highlight'   => $dailyTrainingStats['today_checkins'] . ' check-in hari ini',
            'preview'     => 'Jam kehadiran member',
            'details'     => ['Jam check-in', 'Jumlah hadir per hari', 'Member hadir', 'Status kehadiran'],
        ],
        [
            'title'       => 'Laporan Vitamin',
            'group'       => 'Operasional',
            'summary'     => 'Daftar stok vitamin yang tersimpan di database produk.',
            'date_label'  => 'Per ' . now()->format('d M Y'),
            'count_label' => $vitaminProductRecords->count() . ' vitamin',
            'highlight'   => $vitaminProductRecords->where('stock', '<', 3)->count() . ' stok rendah',
            'preview'     => 'Stok vitamin tersedia',
            'details'     => ['Nama vitamin', 'Brand dan SKU', 'Stok saat ini', 'Harga jual', 'Status produk'],
        ],
    ])->map(function ($report) {
        $report = array_merge([
            'daily_stat_columns'   => ['Tanggal', 'Jumlah'],
            'daily_stat_rows'      => [],
            'monthly_stat_columns' => ['Bulan', 'Jumlah'],
            'monthly_stat_rows'    => [],
            'columns'              => ['Informasi'],
            'rows'                 => [],
            'overview'             => [],
        ], $report);

        $report['slug'] = Str::slug($report['title']);
        return $report;
    })->map(function ($report) use ($memberRecords, $filteredMemberRecords, $checkinRecords, $detailMonth, $detailRangeStart, $detailRangeEnd, $buildDailyRows, $buildMonthlyRows, $formatMoney, $verifiedTransactions, $expenseRecords, $memberPaymentRecords, $dailyPassPaymentRecords, $productSaleRecords, $vitaminProductRecords, $stockLogRecords) {
        $stockSales = $productSaleRecords
            ->filter(fn ($transaction) => $transaction->transaction_at && $transaction->transaction_at->betweenIncluded($detailRangeStart, $detailRangeEnd));

        $dummyDetails = match ($report['slug']) {
            'aktivitas-latihan' => [
                'daily_stat_columns' => ['Tanggal', 'Kehadiran'],
                'daily_stat_rows' => $buildDailyRows($detailRangeStart, $detailRangeEnd, function (Carbon $date) {
                    $count = 12 + ($date->day % 7) * 2;
                    return [$date->translatedFormat('d M'), $count];
                }),
                'monthly_stat_columns' => ['Bulan', 'Kehadiran'],
                'monthly_stat_rows' => $buildMonthlyRows($detailMonth->year, function (Carbon $date) {
                    $count = 320 + ($date->month * 12);
                    return [$date->translatedFormat('M'), $count];
                }),
                'columns' => ['Tanggal', 'Member', 'Jenis Aktivitas', 'Status'],
                'rows' => [
                    ['01 ' . $detailMonth->translatedFormat('M Y'), 'Andi', 'Latihan Bebas', 'Selesai'],
                    ['02 ' . $detailMonth->translatedFormat('M Y'), 'Budi', 'Kelas Strength', 'Selesai'],
                    ['03 ' . $detailMonth->translatedFormat('M Y'), 'Citra', 'Kartu Member', 'Dibatalkan'],
                ],
                'overview' => ['Kehadiran harian', 'Total check-in per hari', 'Detail aktivitas member'],
            ],
            'laporan-member' => [
                'daily_stat_columns' => ['Tanggal', 'Member Aktif', 'Member Baru', 'Expired'],
                'daily_stat_rows' => $buildDailyRows($detailRangeStart, $detailRangeEnd, function (Carbon $date) use ($memberRecords) {
                    $activeOnDate = $memberRecords->filter(fn($member) => $member->joined_at && $member->joined_at->lte($date) && ($member->expires_at === null || $member->expires_at->gte($date)));
                    $newOnDate = $memberRecords->filter(fn($member) => $member->joined_at && $member->joined_at->equalTo($date));
                    $expiredOnDate = $memberRecords->filter(fn($member) => $member->expires_at && $member->expires_at->equalTo($date));

                    $formatNames = fn($collection) => $collection->pluck('full_name')->take(3)->implode(', ') . ($collection->count() > 3 ? ' +'.($collection->count() - 3).' lain' : '');

                    return [
                        $date->translatedFormat('d M'),
                        $activeOnDate->count() . ($activeOnDate->count() ? ' (' . $formatNames($activeOnDate) . ')' : ''),
                        $newOnDate->count() . ($newOnDate->count() ? ' (' . $formatNames($newOnDate) . ')' : ''),
                        $expiredOnDate->count() . ($expiredOnDate->count() ? ' (' . $formatNames($expiredOnDate) . ')' : ''),
                    ];
                }),
                'monthly_stat_columns' => ['Bulan', 'Member Aktif', 'Member Baru', 'Expired'],
                'monthly_stat_rows' => $buildMonthlyRows($detailMonth->year, function (Carbon $date) use ($memberRecords) {
                    $monthStart = $date->copy()->startOfMonth();
                    $monthEnd = $date->copy()->endOfMonth();
                    $activeInMonth = $memberRecords->filter(fn($member) => $member->joined_at && $member->joined_at->lte($monthEnd) && ($member->expires_at === null || $member->expires_at->gte($monthStart)));
                    $newInMonth = $memberRecords->filter(fn($member) => $member->joined_at && $member->joined_at->betweenIncluded($monthStart, $monthEnd));
                    $expiredInMonth = $memberRecords->filter(fn($member) => $member->expires_at && $member->expires_at->betweenIncluded($monthStart, $monthEnd));

                    $formatNames = fn($collection) => $collection->pluck('full_name')->take(3)->implode(', ') . ($collection->count() > 3 ? ' +'.($collection->count() - 3).' lain' : '');

                    return [
                        $date->translatedFormat('M'),
                        $activeInMonth->count() . ($activeInMonth->count() ? ' (' . $formatNames($activeInMonth) . ')' : ''),
                        $newInMonth->count() . ($newInMonth->count() ? ' (' . $formatNames($newInMonth) . ')' : ''),
                        $expiredInMonth->count() . ($expiredInMonth->count() ? ' (' . $formatNames($expiredInMonth) . ')' : ''),
                    ];
                }),
                'columns' => ['Nama Member', 'Jenis Keanggotaan', 'Status', 'Berakhir', 'Tanggal Daftar', 'Metode Pembayaran'],
                'rows' => $filteredMemberRecords->map(fn($member) => [
                    $member->full_name,
                    'Member',
                    $member->expires_at && $member->expires_at->gte(now()) ? 'Aktif' : 'Expired',
                    $member->expires_at?->format('d M Y') ?? '-',
                    $member->joined_at?->format('d M Y') ?? '-',
                    'Cash',
                ])->take(10)->toArray(),
                'member_table_columns' => ['Nama Member', 'Metode Pembayaran', 'Tanggal Daftar', 'Berakhir'],
                'member_table_groups' => [
                    'Aktif' => $filteredMemberRecords->filter(fn($member) => $member->joined_at && $member->joined_at->lte($detailRangeEnd) && ($member->expires_at === null || $member->expires_at->gte(now()))),
                    'Baru' => $filteredMemberRecords->filter(fn($member) => $member->joined_at && $member->joined_at->betweenIncluded($detailMonth->copy()->startOfMonth(), $detailMonth->copy()->endOfMonth())),
                    'Expired' => $filteredMemberRecords->filter(fn($member) => $member->expires_at && $member->expires_at->betweenIncluded($detailMonth->copy()->startOfMonth(), $detailMonth->copy()->endOfMonth())),
                ],
                'overview' => ['Jumlah member aktif', 'Member baru', 'Member expired', 'Riwayat pendaftaran', 'Data paket member'],
            ],
            'laporan-keuangan' => [
                'daily_stat_columns' => ['Tanggal', 'Total Pemasukan', 'Pembayaran Member', 'Jumlah Transaksi'],
                'daily_stat_rows' => $buildDailyRows($detailRangeStart, $detailRangeEnd, function (Carbon $date) {
                    $income = 1200000 + ($date->day * 8000);
                    $memberPayment = 700000 + ($date->day * 2500);
                    return [$date->translatedFormat('d M'), $income, $memberPayment, $date->day % 4 + 3 . ' transaksi'];
                }),
                'monthly_stat_columns' => ['Bulan', 'Total Pemasukan', 'Pembayaran Member', 'Transaksi'],
                'monthly_stat_rows' => $buildMonthlyRows($detailMonth->year, function (Carbon $date) {
                    $income = 36000000 + ($date->month * 1000000);
                    $memberPayment = 21000000 + ($date->month * 500000);
                    return [$date->translatedFormat('M'), $income, $memberPayment, 520 + ($date->month * 10) . ' transaksi'];
                }),
                'columns' => ['Tanggal', 'Keterangan', 'Total Pemasukan', 'Pembayaran Member'],
                'rows' => [
                    [$detailMonth->copy()->startOfMonth()->format('d M Y'), 'Pembayaran member', 850000, 850000],
                    [$detailMonth->copy()->addDays(3)->format('d M Y'), 'Penjualan produk', 425000, 0],
                    [$detailMonth->copy()->addDays(5)->format('d M Y'), 'Pendapatan harian', 1200000, 0],
                ],
                'reportSections' => [
                    'Sumber Pendapatan' => [
                        'columns' => ['Sumber', 'Jumlah', 'Catatan'],
                        'rows' => [
                            ['Pembayaran member', $formatMoney($memberPaymentRecords->filter(fn($transaction) => $transaction->transaction_at && $transaction->transaction_at->betweenIncluded($detailRangeStart, $detailRangeEnd))->sum('amount')), 'Pembayaran paket dan perpanjangan'],
                            ['Pembayaran daily pass', $formatMoney($dailyPassPaymentRecords->filter(fn($transaction) => $transaction->transaction_at && $transaction->transaction_at->betweenIncluded($detailRangeStart, $detailRangeEnd))->sum('amount')), 'Daily pass dan pengunjung'],
                            ['Penjualan produk', $formatMoney($productSaleRecords->filter(fn($transaction) => $transaction->transaction_at && $transaction->transaction_at->betweenIncluded($detailRangeStart, $detailRangeEnd))->sum('amount')), 'Suplemen, minuman, peralatan kecil'],
                        ],
                    ],
                    'Pembayaran' => [
                        'columns' => ['Tanggal Pembayaran', 'Nama', 'Kelompok', 'Jumlah', 'Metode Pembayaran'],
                        'rows' => $memberPaymentRecords->filter(fn($transaction) => $transaction->transaction_at && $transaction->transaction_at->betweenIncluded($detailRangeStart, $detailRangeEnd))->take(5)->map(fn($transaction) => [
                            $transaction->transaction_at?->translatedFormat('d M Y') ?? '-',
                            $transaction->member?->full_name ?? $transaction->customer_name ?? 'Tidak dikenal',
                            'Member',
                            $formatMoney($transaction->amount),
                            ucfirst($transaction->payment_method ?? 'Cash'),
                        ])->toBase()->merge($dailyPassPaymentRecords->filter(fn($transaction) => $transaction->transaction_at && $transaction->transaction_at->betweenIncluded($detailRangeStart, $detailRangeEnd))->take(5)->map(fn($transaction) => [
                            $transaction->transaction_at?->translatedFormat('d M Y') ?? '-',
                            $transaction->customer_name ?? 'Daily Pass',
                            'Daily Pass',
                            $formatMoney($transaction->amount),
                            ucfirst($transaction->payment_method ?? 'Cash'),
                        ])->toBase())->take(10)->values()->toArray(),
                    ],
                    'Penjualan Produk' => [
                        'columns' => ['Tanggal', 'Pelanggan', 'Produk', 'Jumlah', 'Metode Pembayaran'],
                        'rows' => $productSaleRecords->filter(fn($transaction) => $transaction->transaction_at && $transaction->transaction_at->betweenIncluded($detailRangeStart, $detailRangeEnd))->map(fn($transaction) => [
                            $transaction->transaction_at?->translatedFormat('d M Y') ?? '-',
                            $transaction->customer_name ?? ($transaction->member?->full_name ?? 'Pelanggan'),
                            $transaction->description ?? 'Produk',
                            $formatMoney($transaction->amount),
                            ucfirst($transaction->payment_method ?? 'Cash'),
                        ])->toBase()->values()->toArray(),
                    ],
                    'Riwayat Pemasukan' => [
                        'columns' => ['Tanggal', 'Sumber', 'Keterangan', 'Jumlah', 'Metode Pembayaran'],
                        'rows' => $verifiedTransactions->filter(fn($transaction) => $transaction->transaction_at && $transaction->transaction_at->betweenIncluded($detailRangeStart, $detailRangeEnd))->map(fn($transaction) => [
                            $transaction->transaction_at ? $transaction->transaction_at->format('d M Y') : '-',
                            Transaction::typeLabel($transaction->type),
                            $transaction->customer_name ?? ($transaction->member?->full_name ?? '-'),
                            $formatMoney($transaction->amount),
                            ucfirst($transaction->payment_method ?? 'Cash'),
                        ])->sortByDesc(fn($row) => Carbon::parse($row[0]))->values()->toArray(),
                    ],
                    'Riwayat Pengeluaran' => [
                        'columns' => ['Tanggal', 'Kategori', 'Keterangan', 'Jumlah', 'Metode Pembayaran'],
                        'rows' => $expenseRecords->filter(fn($expense) => $expense->expense_date && $expense->expense_date->betweenIncluded($detailRangeStart, $detailRangeEnd))->map(fn($expense) => [
                            $expense->expense_date->format('d M Y'),
                            ucfirst(str_replace('_', ' ', $expense->category ?? 'pengeluaran')),
                            $expense->title,
                            $formatMoney($expense->amount),
                            ucfirst($expense->payment_method ?? 'Tunai'),
                        ])->sortByDesc(fn($row) => Carbon::parse($row[0]))->values()->toArray(),
                    ],
                ],
                'overview' => ['Total pemasukan', 'Pembayaran member', 'Pembayaran daily pass', 'Riwayat pemasukan', 'Riwayat pengeluaran'],
            ],
            'laporan-kehadiran' => [
                'daily_stat_columns' => ['Tanggal', 'Jumlah Check-in', 'Member Hadir', 'Check-in Terakhir'],
                'daily_stat_rows' => $buildDailyRows($detailRangeStart, $detailRangeEnd, function (Carbon $date) use ($checkinRecords) {
                    $dailyCheckins = $checkinRecords->filter(fn($checkin) => $checkin->checked_in_at && $checkin->checked_in_at->isSameDay($date));
                    $memberNames = $dailyCheckins
                        ->map(fn($checkin) => $checkin->member?->full_name ?? $checkin->submitted_name ?? 'Tidak dikenal')
                        ->unique()
                        ->values();

                    return [
                        $date->translatedFormat('d M'),
                        $dailyCheckins->count(),
                        $memberNames->take(3)->implode(', ') . ($memberNames->count() > 3 ? ' +' . ($memberNames->count() - 3) . ' lain' : ''),
                        $dailyCheckins->sortByDesc('checked_in_at')->first()?->checked_in_at?->format('H:i') ?? '-',
                    ];
                }),
                'monthly_stat_columns' => ['Bulan', 'Jumlah Check-in', 'Member Unik', 'Rata-rata per Hari'],
                'monthly_stat_rows' => $buildMonthlyRows($detailMonth->year, function (Carbon $date) use ($checkinRecords) {
                    $monthStart = $date->copy()->startOfMonth();
                    $monthEnd = $date->copy()->endOfMonth();
                    $monthlyCheckins = $checkinRecords->filter(fn($checkin) => $checkin->checked_in_at && $checkin->checked_in_at->betweenIncluded($monthStart, $monthEnd));
                    $uniqueMembers = $monthlyCheckins
                        ->map(fn($checkin) => $checkin->member_id ?: ($checkin->submitted_name . '|' . $checkin->submitted_phone))
                        ->filter()
                        ->unique()
                        ->count();

                    return [
                        $date->translatedFormat('M'),
                        $monthlyCheckins->count(),
                        $uniqueMembers,
                        number_format($monthlyCheckins->count() / max($date->daysInMonth, 1), 1, ',', '.'),
                    ];
                }),
                'columns' => ['Tanggal', 'Nama Member', 'No. HP', 'Waktu Check-in', 'Metode', 'Status', 'Catatan'],
                'rows' => $checkinRecords
                    ->filter(fn($checkin) => $checkin->checked_in_at && $checkin->checked_in_at->betweenIncluded($detailRangeStart, $detailRangeEnd))
                    ->sortByDesc('checked_in_at')
                    ->map(fn($checkin) => [
                        $checkin->checked_in_at?->translatedFormat('d M Y') ?? '-',
                        $checkin->member?->full_name ?? $checkin->submitted_name ?? 'Tidak dikenal',
                        $checkin->member?->phone ?? $checkin->submitted_phone ?? '-',
                        $checkin->checked_in_at?->format('H:i') ?? '-',
                        ucfirst($checkin->checkin_method ?? 'admin'),
                        ucfirst($checkin->verification_status ?? 'verified'),
                        $checkin->notes ?: '-',
                    ])->values()->toArray(),
                'overview' => ['Daftar member check-in', 'Jumlah hadir per hari', 'Member unik bulanan', 'Status verifikasi'],
            ],
            'laporan-vitamin' => [
                'daily_stat_columns' => ['Tanggal', 'Total Jenis Vitamin', 'Total Stok', 'Stok Rendah', 'Terjual'],
                'daily_stat_rows' => [[
                    now()->translatedFormat('d M'),
                    $vitaminProductRecords->count(),
                    $vitaminProductRecords->sum('stock'),
                    $vitaminProductRecords->where('stock', '<', 3)->count(),
                    $stockSales->sum('amount'),
                ]],
                'monthly_stat_columns' => ['Bulan', 'Total Jenis Vitamin', 'Total Stok', 'Stok Rendah', 'Terjual'],
                'monthly_stat_rows' => [[
                    $detailMonth->translatedFormat('M'),
                    $vitaminProductRecords->count(),
                    $vitaminProductRecords->sum('stock'),
                    $vitaminProductRecords->where('stock', '<', 3)->count(),
                    $stockSales->sum('amount'),
                ]],
                'columns' => ['Nama Vitamin', 'Brand', 'SKU', 'Stok', 'Harga', 'Status'],
                'rows' => $vitaminProductRecords->map(fn($product) => [
                    $product->name,
                    $product->brand ?: '-',
                    $product->sku ?: '-',
                    number_format($product->stock, 0, ',', '.') . ' ' . ($product->unit ?? 'pcs'),
                    $formatMoney($product->price),
                    $product->is_active ? 'Aktif' : 'Nonaktif',
                ])->toArray(),
                'overview' => ['Nama vitamin', 'Brand dan SKU', 'Stok saat ini', 'Harga jual', 'Status produk'],
            ],
            'laporan-stok-barang' => [
                'columns' => ['Waktu', 'Produk', 'Tipe', 'Jumlah', 'Keterangan', 'Oleh'],
                'rows' => $stockLogRecords->map(fn($log) => [
                    $log->created_at?->format('d M Y H:i') ?? '-',
                    $log->product?->name ?? 'Produk Dihapus',
                    $log->type === 'in' ? 'Masuk' : ($log->type === 'out' ? 'Keluar' : 'Penyesuaian'),
                    $log->quantity,
                    $log->description ?? '-',
                    $log->user?->name ?? '-',
                ])->toArray(),
                'overview' => ['Waktu pergerakan', 'Nama produk terkait', 'Jenis pergerakan', 'Jumlah', 'Catatan/Keterangan', 'Pengguna sistem'],
            ],
            default => [],
        };

        return array_merge($report, $dummyDetails);
    });

    return [
        'financialSummary'        => $financialSummary,
        'financialMonthlyHistory' => $financialMonthlyHistory,
        'dailyTrainingStats'      => $dailyTrainingStats,
        'membershipReportSummary' => $membershipReportSummary,
        'reportCatalog'           => $reportCatalog,
        'detailFilterMonth'       => $detailMonth->format('Y-m'),
        'detailFilterLabel'       => $reportDateLabel,
        'visitorDailyRows'        => $visitorDailyRows,
        'recentTrainingActivities'=> collect(),
        'membershipOperationalRows'=> collect(),
        'expenseRecords'          => collect(),
        'memberPaymentRecords'    => collect(),
        'dailyPassPaymentRecords' => collect(),
        'otherTransactionRecords' => collect(),
        'stockLogRecords'         => $stockLogRecords,
    ];
};

// ── Routes ────────────────────────────────────────────────────────────────────
$buildExcelResponse = function (array $selectedReport, array $reportData) {
    $escape = fn($value): string => e((string) $value);

    $renderTable = function (string $title, array $columns, iterable $rows) use ($escape): string {
        $rows = collect($rows)->values();
        $colspan = max(count($columns), 1);
        $html = '<h2>' . $escape($title) . '</h2><table><thead><tr>';

        foreach ($columns as $column) {
            $html .= '<th>' . $escape($column) . '</th>';
        }

        $html .= '</tr></thead><tbody>';

        if ($rows->isEmpty()) {
            $html .= '<tr><td colspan="' . $colspan . '" style="text-align:center;">Data tidak ditemukan</td></tr>';
        } else {
            foreach ($rows as $row) {
                $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= '<td>' . $escape($cell) . '</td>';
                }
                $html .= '</tr>';
            }
        }

        $html .= '</tbody></table><br/>';

        return $html;
    };

    $filename = 'laporan-' . $selectedReport['slug'] . '-' . now()->format('Ymd-His') . '.xls';

    $html = '<html><head><meta charset="utf-8"/><style>'
        . 'body { font-family: Arial, sans-serif; font-size: 12px; }'
        . 'table { border-collapse: collapse; width: 100%; margin-bottom: 16px; }'
        . 'th, td { border: 1px solid #d1d5db; padding: 6px 8px; text-align: left; vertical-align: top; }'
        . 'th { background-color: #f3f4f6; font-weight: bold; }'
        . 'h1 { font-size: 18px; margin-bottom: 4px; }'
        . 'h2 { font-size: 14px; margin-top: 14px; margin-bottom: 6px; }'
        . '.meta { margin-bottom: 12px; color: #4b5563; }'
        . '</style></head><body>';

    $html .= '<h1>' . $escape($selectedReport['title']) . '</h1>';
    $html .= '<div class="meta">Periode: ' . $escape($selectedReport['date_label']) . ' | Dibuat: ' . $escape(now()->translatedFormat('d F Y H:i')) . '</div>';

    if (! empty($selectedReport['daily_stat_columns']) && ! empty($selectedReport['daily_stat_rows'])) {
        $html .= $renderTable('Statistik Harian', $selectedReport['daily_stat_columns'], $selectedReport['daily_stat_rows']);
    }

    if (! empty($selectedReport['monthly_stat_columns']) && ! empty($selectedReport['monthly_stat_rows'])) {
        $html .= $renderTable('Statistik Bulanan', $selectedReport['monthly_stat_columns'], $selectedReport['monthly_stat_rows']);
    }

    if (! empty($selectedReport['columns']) && ! empty($selectedReport['rows'])) {
        $html .= $renderTable('Data Rinci', $selectedReport['columns'], $selectedReport['rows']);
    }

    $html .= '</body></html>';

    return response($html, 200, [
        'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        'Cache-Control' => 'max-age=0, no-cache, no-store, must-revalidate',
        'Pragma' => 'no-cache',
    ]);
};

$buildMemberExportResponse = function () {
    $members = Member::orderBy('full_name')->get();
    $escape = fn($value): string => e((string) $value);
    $filename = 'data-member-' . now()->format('Ymd-His') . '.xls';

    $html = '<html><head><meta charset="utf-8"/><style>'
        . 'body { font-family: Arial, sans-serif; font-size: 12px; }'
        . 'table { border-collapse: collapse; width: 100%; }'
        . 'th, td { border: 1px solid #d1d5db; padding: 6px 8px; text-align: left; }'
        . 'th { background-color: #f3f4f6; font-weight: bold; }'
        . 'h1 { font-size: 16px; margin-bottom: 8px; }'
        . '</style></head><body>';

    $html .= '<h1>Data Member Arena Gym</h1>';
    $html .= '<p>Tanggal Ekspor: ' . now()->translatedFormat('d F Y H:i') . '</p>';
    $html .= '<table><thead><tr>'
        . '<th>Nama Lengkap</th><th>Email</th><th>Telepon</th>'
        . '<th>Tgl Daftar</th><th>Masa Aktif</th><th>Catatan</th>'
        . '</tr></thead><tbody>';

    foreach ($members as $member) {
        $html .= '<tr>';
        foreach ([
            $member->full_name,
            $member->email ?? '-',
            $member->phone ?? '-',
            $member->joined_at?->format('d M Y') ?? '-',
            $member->expires_at?->format('d M Y') ?? '-',
            $member->notes ?? '-',
        ] as $cell) {
            $html .= '<td>' . $escape($cell) . '</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</tbody></table></body></html>';

    return response($html, 200, [
        'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        'Cache-Control' => 'max-age=0, no-cache, no-store, must-revalidate',
        'Pragma' => 'no-cache',
    ]);
};

Route::get('/reports', function (Request $request) use ($buildAdminReportsData) {
    if ($redirect = RouteHelpers::ensureAdmin()) return $redirect;
    return view('admin.reports', array_merge(RouteHelpers::pageMeta('reports'), $buildAdminReportsData($request)));
})->name('reports');

Route::get('/reports/{reportSlug}', function (Request $request, string $reportSlug) use ($buildAdminReportsData, $buildExcelResponse) {
    if ($redirect = RouteHelpers::ensureAdmin()) return $redirect;
    $reportData = $buildAdminReportsData($request);
    $selectedReport = collect($reportData['reportCatalog'])->firstWhere('slug', $reportSlug);
    abort_unless($selectedReport, 404);

    if ($request->boolean('export')) {
        return $buildExcelResponse($selectedReport, $reportData);
    }

    return view('admin.report-detail', array_merge(RouteHelpers::pageMeta('reports'), $reportData, ['selectedReport' => $selectedReport]));
})->name('reports.show');

Route::get('/export/member-data', function () use ($buildMemberExportResponse) {
    if ($redirect = RouteHelpers::ensureAdmin()) return $redirect;

    return $buildMemberExportResponse();
})->name('export.member-data');

Route::post('/reports/expenses', function (Request $request) {
    if ($redirect = RouteHelpers::ensureAdmin()) return $redirect;
    ExpenseRecord::create($request->validate([
        'title' => 'required|string|max:255',
        'category' => 'nullable|string|max:60',
        'amount' => 'required|integer|min:1',
        'payment_method' => 'nullable|in:cash,qris,transfer',
        'expense_date' => 'required|date',
        'notes' => 'nullable|string',
    ]));
    return back()->with('status', 'Pengeluaran berhasil ditambahkan.');
})->name('reports.expenses.store');
