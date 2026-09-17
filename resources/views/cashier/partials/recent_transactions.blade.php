            <div class="cashier-panel elevated">
                <div class="cashier-panel-header">
                    <div class="cashier-panel-title">
                        <span class="cashier-panel-title-icon"><i class="fas fa-receipt"></i></span>
                        <div>
                            <div class="section-label">Riwayat Hari Ini</div>
                            <h2 class="h5 fw-bold mt-2 mb-0">Transaksi Terbaru</h2>
                        </div>
                    </div>
                    <div class="cashier-toolbar">
                        <label class="cashier-search" for="transactionSearch">
                            <i class="fas fa-search"></i>
                            <input id="transactionSearch" type="search" placeholder="Cari transaksi..." data-transaction-search>
                        </label>
                        <a href="{{ route('cashier.transactions') }}" class="btn btn-dark rounded-pill px-4 fw-semibold">Lihat Semua</a>
                    </div>
                </div>
                <div class="table-responsive cashier-table-wrap" data-table-scroll-main>
                    <table class="table cashier-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Invoice</th>
                                <th>Pelanggan</th>
                                <th>Tipe</th>
                                <th>Nominal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dashboardTransactions as $item)
                                @php
                                    $customerName = $item->customer_name ?? $item->member?->full_name ?? 'Tidak dikenal';
                                    $transactionType = ucfirst(str_replace('_', ' ', $item->transaction_group ?? $item->transaction_type ?? '-'));
                                    $initials = collect(explode(' ', trim($customerName)))
                                        ->filter()
                                        ->take(2)
                                        ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
                                        ->implode('') ?: 'AF';
                                    $isPaid = $item->payment_status === 'verified';
                                    $paymentMethod = $item->payment_method === 'later' ? 'Bayar Nanti' : strtoupper((string) $item->payment_method);
                                    $receiptLabel = $item->receipt_status === 'printed'
                                        ? 'Sudah Dicetak'
                                        : ($item->receipt_status === 'ready' ? 'Siap Cetak' : 'Menunggu Verifikasi');
                                    $transactionDetail = [
                                        'invoice' => $item->invoice,
                                        'customer' => $customerName,
                                        'type' => $transactionType,
                                        'amount' => 'Rp' . number_format($item->amount, 0, ',', '.'),
                                        'paidAmount' => 'Rp' . number_format($item->paid_amount ?? $item->amount, 0, ',', '.'),
                                        'changeAmount' => 'Rp' . number_format($item->change_amount ?? 0, 0, ',', '.'),
                                        'paymentMethod' => $paymentMethod,
                                        'paymentStatus' => $isPaid ? 'Lunas' : 'Pending',
                                        'receiptStatus' => $receiptLabel,
                                        'time' => $item->transaction_at?->format('H:i, d M Y') ?? '-',
                                        'quantity' => (string) ($item->quantity ?? 1),
                                        'notes' => $item->notes ?: '-',
                                    ];
                                @endphp
                                <tr
                                    role="button"
                                    tabindex="0"
                                    aria-label="Lihat detail transaksi {{ $item->invoice }}"
                                    data-transaction-row
                                    data-search="{{ strtolower($item->invoice . ' ' . $customerName . ' ' . $transactionType . ' ' . $item->amount) }}"
                                    data-detail='@json($transactionDetail)'
                                >
                                    <td data-label="Waktu">
                                        <span class="time-stack">
                                            <strong>{{ $item->transaction_at?->format('H:i') ?? '-' }}</strong>
                                            <span>Hari ini</span>
                                        </span>
                                    </td>
                                    <td data-label="Invoice"><span class="invoice-chip"><span class="invoice-chip-text">{{ $item->invoice }}</span></span></td>
                                    <td data-label="Pelanggan">
                                        <div class="customer-cell">
                                            <span class="customer-avatar">{{ $initials }}</span>
                                            <span>
                                                <span class="customer-name">{{ $customerName }}</span>
                                                <span class="customer-sub">Arena Fitness</span>
                                            </span>
                                        </div>
                                    </td>
                                    <td data-label="Tipe"><span class="type-pill"><i class="fas fa-layer-group"></i>{{ $transactionType }}</span></td>
                                    <td data-label="Nominal">
                                        <span class="amount-text">Rp{{ number_format($item->amount, 0, ',', '.') }}</span>
                                        <span class="small muted-copy amount-subtext">Terima Rp{{ number_format($item->paid_amount ?? $item->amount, 0, ',', '.') }} • Kembali Rp{{ number_format($item->change_amount ?? 0, 0, ',', '.') }}</span>
                                    </td>
                                    <td data-label="Status">
                                        <span class="status-pill {{ $isPaid ? 'is-paid' : 'is-pending' }}">
                                            <i class="fas {{ $isPaid ? 'fa-check' : 'fa-clock' }}"></i>
                                            {{ $isPaid ? 'Lunas' : 'Pending' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4">
                                        <div class="cashier-empty">Belum ada transaksi hari ini.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="table-scroll-hint">
                        <span>5 transaksi terbaru</span>
                        <span>Geser kanan/kiri</span>
                    </div>
                    <div class="cashier-empty d-none m-3" data-transaction-empty>Transaksi tidak ditemukan.</div>
                </div>
            </div>

