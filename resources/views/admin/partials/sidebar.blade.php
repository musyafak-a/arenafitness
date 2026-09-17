@php
    $adminNavigation = [
        ['key' => 'dashboard', 'label' => 'Dashboard', 'route' => route('admin.dashboard')],
        ['key' => 'members', 'label' => 'Member', 'route' => route('admin.members')],
        ['key' => 'feedbacks', 'label' => 'Kritik & Saran', 'route' => route('admin.feedbacks')],
        ['key' => 'announcements', 'label' => 'Pengumuman', 'route' => route('admin.announcements')],
        ['key' => 'profile-photo-requests', 'label' => 'Persetujuan Foto', 'route' => route('admin.profile-photo-requests')],
        ['key' => 'products', 'label' => 'Produk', 'route' => route('admin.products')],
        ['key' => 'reports', 'label' => 'Laporan', 'route' => route('admin.reports')],
    ];
    $cashierNavigation = [
        ['key' => 'cashier.dashboard', 'label' => 'Dashboard', 'route' => route('cashier.dashboard')],
        ['key' => 'cashier.checkins', 'label' => 'Check-in', 'route' => route('cashier.checkins')],
        ['key' => 'cashier.transactions', 'label' => 'Transaksi', 'route' => route('cashier.transactions')],
        ['key' => 'cashier.receipts', 'label' => 'Verifikasi QRIS', 'route' => route('cashier.receipts')],
    ];
    $navigationGroups = $isMasterAdmin
        ? [
            ['label' => 'Admin', 'items' => $adminNavigation],
            ['label' => 'Kasir', 'items' => $cashierNavigation],
        ]
        : [
            [
                'label' => $isCashierArea ? 'Kasir' : 'Admin',
                'items' => $isCashierArea ? $cashierNavigation : $adminNavigation,
            ],
        ];
@endphp

<aside class="col-12 col-lg-3 col-xl-3 sidebar-column {{ $isCashierArea ? 'cashier-sidebar' : '' }}">
    <div class="sidebar p-3 p-lg-4" id="adminSidebar">
        <div class="sidebar-stack sidebar-inner">
            <div class="sidebar-main">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <img src="{{ asset('images/arena-fitness-logo.jpg') }}" alt="Arena Fitness" class="brand-logo">
                    <div>
                        <div class="fw-bold">Arena Fitness</div>
                        <div class="small text-white-50">
                            {{ $isMasterAdmin ? 'Master Admin' : ($isCashierArea ? 'Kasir' : 'Admin') }}
                        </div>
                    </div>
                </div>

                <div class="small text-uppercase text-white-50 fw-bold mb-1" style="letter-spacing:.12em;">Menu</div>
                @foreach ($navigationGroups as $group)
                    <div class="sidebar-nav-group">
                        <nav class="sidebar-nav-list">
                            @foreach ($group['items'] as $item)
                                @php
                                    $isActive = $item['key'] === 'cashier.receipts'
                                        ? in_array(($activePage ?? ''), ['cashier.verifications', 'cashier.receipts'], true)
                                        : ($item['key'] === 'cashier.transactions'
                                            ? in_array(($activePage ?? ''), ['cashier.transactions', 'cashier.member-payments', 'cashier.daily-payments'], true)
                                            : (($activePage ?? 'dashboard') === $item['key']));
                                @endphp
                                <a class="sidebar-link {{ $isActive ? 'active' : '' }}" href="{{ $item['route'] }}">
                                    <span class="sidebar-link-label">{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </nav>
                    </div>
                @endforeach
            </div>

            <div class="sidebar-bottom">
            @if (! $isCashierArea && ! empty($sidebarExtraSummary))
                <div class="sidebar-extra-card p-3">
                    <div class="section-label text-white-50">{{ $sidebarExtraSummary['label'] ?? 'Ringkasan' }}</div>
                    <div class="h4 fw-bold mt-3 mb-1">{{ $sidebarExtraSummary['title'] ?? '' }}</div>
                    <div class="small muted-copy">{{ $sidebarExtraSummary['note'] ?? '' }}</div>
                </div>
            @endif

            @if (! $isCashierArea && ! empty($sidebarExtraItems))
                <div class="sidebar-extra-card p-3">
                    <div class="section-label text-white-50 mb-3">{{ $sidebarExtraItemsTitle ?? 'Prioritas' }}</div>
                    <div class="d-grid gap-3">
                        @foreach ($sidebarExtraItems as $item)
                            <div>
                                <div class="fw-semibold">{{ $item['title'] ?? '' }}</div>
                                <div class="small muted-copy mt-1">{{ $item['note'] ?? '' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light w-100 rounded-pill">Logout</button>
            </form>
            </div>
        </div>
    </div>
</aside>
