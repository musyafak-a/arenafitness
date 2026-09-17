        <section class="cashier-hero" data-hero-spotlight>
            <div class="cashier-hero-main">
                <div>
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ asset('images/arena-fitness-logo.jpg') }}" alt="Arena Fitness" class="brand-logo-inline">
                            <div>
                                <div class="cashier-kicker">Arena Fitness Cashier</div>
                            </div>
                        </div>
                        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Ganti tema">
                            <span class="theme-toggle-track" aria-hidden="true">
                                <span class="theme-toggle-thumb"></span>
                            </span>
                        </button>
                    </div>
                </div>

                <div class="cashier-actions">
                    <a href="{{ route('cashier.transactions') }}" class="cashier-action primary">
                        <span class="cashier-action-icon"><i class="fas fa-credit-card"></i></span>
                        <span>Transaksi</span>
                    </a>
                    <a href="{{ route('cashier.checkins') }}" class="cashier-action">
                        <span class="cashier-action-icon"><i class="fas fa-person-walking"></i></span>
                        <span>Check-in</span>
                    </a>
                    <a href="{{ route('cashier.transactions.products') }}" class="cashier-action">
                        <span class="cashier-action-icon"><i class="fas fa-basket-shopping"></i></span>
                        <span>Checkout Barang</span>
                    </a>
                </div>
            </div>

            <div class="cashier-hero-side">
                <div class="shift-card"
                    data-shift-start="{{ $cashierShift['start'] ?? '08:00' }}"
                    data-shift-end="{{ $cashierShift['end'] ?? '16:00' }}">
                    <div class="shift-top">
                        <div class="section-label text-white-50">Shift Aktif</div>
                        <span class="shift-status">Online</span>
                    </div>
                    <div class="shift-time">{{ $cashierShift['label'] ?? '08:00 - 16:00' }}</div>
                    <div class="shift-countdown js-shift-countdown">Menghitung waktu shift...</div>
                </div>

                <div class="cashier-image-card">
                    <div>
                        <strong>Operasional hari ini</strong>
                        <span>Transaksi masuk, bukti dicek, dan laporan tetap rapi.</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="cashier-stats" aria-label="Ringkasan dashboard kasir">
            @foreach ($cashierStats as $index => $stat)
                @php
                    $icons = ['fa-credit-card', 'fa-ticket', 'fa-receipt', 'fa-chart-line'];
                    $icon = $icons[$index] ?? 'fa-circle-info';
                @endphp
                <div class="cashier-stat">
                    <div class="cashier-stat-head">
                        <div class="cashier-stat-label">{{ $stat['label'] }}</div>
                        <span class="cashier-stat-icon"><i class="fas {{ $icon }}"></i></span>
                    </div>
                    <div class="cashier-stat-value">{{ $stat['value'] }}</div>
                    <span class="cashier-stat-note">{{ $stat['change'] }}</span>
                </div>
            @endforeach
        </section>
