    <div class="dashboard-toast" data-dashboard-toast>Filter transaksi aktif.</div>

    <div class="history-overlay" data-history-overlay></div>
    <aside class="history-sidebar" data-history-sidebar aria-hidden="true" aria-label="Riwayat transaksi">
        <div class="history-head">
            <div>
                <div class="section-label">Riwayat Transaksi</div>
                <h2 class="h5 fw-bold mt-2 mb-1">Semua Pembayaran</h2>
                <p class="muted-copy mb-0">Dipisahkan berdasarkan member, daily pass, dan produk.</p>
            </div>
            <button class="history-close" type="button" data-history-close aria-label="Tutup riwayat transaksi">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="history-tabs" role="tablist" aria-label="Kategori riwayat transaksi">
            <button class="history-tab active" type="button" data-history-tab="member">Member</button>
            <button class="history-tab" type="button" data-history-tab="daily-pass">Daily Pass</button>
            <button class="history-tab" type="button" data-history-tab="produk">Produk</button>
        </div>

        <div class="history-body">
            @foreach ([
                'member' => ['items' => $historyMemberTransactions, 'icon' => 'fa-id-card', 'empty' => 'Belum ada riwayat pembayaran member.'],
                'daily-pass' => ['items' => $historyDailyPassTransactions, 'icon' => 'fa-person-walking', 'empty' => 'Belum ada riwayat daily pass.'],
                'produk' => ['items' => $historyProductTransactions, 'icon' => 'fa-basket-shopping', 'empty' => 'Belum ada riwayat produk.'],
            ] as $historyKey => $history)
                <div class="history-section {{ $historyKey === 'member' ? 'active' : '' }}" data-history-section="{{ $historyKey }}">
                    @forelse ($history['items'] as $historyItem)
                        @php
                            $historyCustomer = $historyItem->customer_name ?? $historyItem->member?->full_name ?? 'Tidak dikenal';
                            $historyType = ucfirst(str_replace('_', ' ', $historyItem->transaction_group ?? $historyItem->transaction_type ?? '-'));
                            $historyStatus = $historyItem->payment_status === 'verified' ? 'Lunas' : 'Pending';
                            $historyStatusClass = $historyItem->payment_status === 'verified' ? 'is-paid' : 'is-pending';
                        @endphp
                        <div class="history-item">
                            <span class="history-icon"><i class="fas {{ $history['icon'] }}"></i></span>
                            <div>
                                <div class="history-title">{{ $historyItem->invoice }}</div>
                                <div class="history-meta">
                                    {{ $historyCustomer }} - {{ $historyType }}<br>
                                    {{ $historyItem->transaction_at?->format('d M Y H:i') ?? '-' }} - {{ strtoupper((string) $historyItem->payment_method) }}
                                </div>
                                <div class="history-foot">
                                    <span class="history-amount">Rp{{ number_format($historyItem->amount, 0, ',', '.') }}</span>
                                    <span class="small muted-copy">Terima Rp{{ number_format($historyItem->paid_amount ?? $historyItem->amount, 0, ',', '.') }} • Kembali Rp{{ number_format($historyItem->change_amount ?? 0, 0, ',', '.') }}</span>
                                    <span class="status-pill {{ $historyStatusClass }}">{{ $historyStatus }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="cashier-empty">{{ $history['empty'] }}</div>
                    @endforelse
                </div>
            @endforeach
        </div>
    </aside>
