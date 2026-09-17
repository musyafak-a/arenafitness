                <div class="cashier-panel elevated">
                    <div class="cashier-panel-header">
                        <div class="cashier-panel-title">
                            <span class="cashier-panel-title-icon"><i class="fas fa-file-invoice"></i></span>
                            <div>
                                <div class="section-label">Perlu Dicek</div>
                                <h2 class="h5 fw-bold mt-2 mb-0">Bukti Pembayaran</h2>
                            </div>
                        </div>
                        <a href="{{ route('cashier.receipts') }}" class="btn btn-outline-secondary rounded-pill px-3 fw-semibold">Buka</a>
                    </div>
                    <div class="cashier-panel-body">
                        <div class="cashier-side-list">
                            @forelse ($dashboardReceiptQueue as $item)
                                @php
                                    $receiptCustomer = $item->customer_name ?? $item->member?->full_name ?? 'Tidak dikenal';
                                    $receiptType = ucfirst(str_replace('_', ' ', $item->transaction_group ?? $item->transaction_type ?? '-'));
                                    $receiptStatusText = $item->receipt_status === 'printed'
                                        ? 'Sudah Dicetak'
                                        : ($item->receipt_status === 'ready' ? 'Siap Cetak' : 'Menunggu Verifikasi');
                                    $receiptDetail = [
                                        'invoice' => $item->invoice,
                                        'customer' => $receiptCustomer,
                                        'type' => $receiptType,
                                        'amount' => 'Rp' . number_format($item->amount, 0, ',', '.'),
                                        'paidAmount' => 'Rp' . number_format($item->paid_amount ?? $item->amount, 0, ',', '.'),
                                        'changeAmount' => 'Rp' . number_format($item->change_amount ?? 0, 0, ',', '.'),
                                        'paymentMethod' => $item->payment_method === 'later' ? 'Bayar Nanti' : strtoupper((string) $item->payment_method),
                                        'paymentStatus' => $item->payment_status === 'verified' ? 'Lunas' : 'Pending',
                                        'receiptStatus' => $receiptStatusText,
                                        'time' => $item->transaction_at?->format('H:i, d M Y') ?? '-',
                                        'printUrl' => route('cashier.receipts.print', $item->invoice),
                                        'verifyUrl' => route('cashier.verifications.confirm', $item->id),
                                        'canPrint' => $item->payment_status === 'verified',
                                        'canFinish' => $item->payment_status !== 'verified',
                                    ];
                                @endphp
                                <div class="cashier-side-item">
                                    <div class="side-item-row">
                                        <span class="receipt-icon"><i class="fas fa-file-lines"></i></span>
                                        <div>
                                            <div class="cashier-side-title">{{ $item->invoice }}</div>
                                            <div class="small muted-copy mt-1">
                                                {{ $receiptCustomer }}
                                                - {{ $receiptType }}
                                            </div>
                                        </div>
                                        <div class="receipt-actions">
                                            <button class="receipt-detail-btn btn btn-sm" type="button" data-receipt-detail='@json($receiptDetail)'>
                                                Lihat Detail
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="cashier-empty">Tidak ada QRIS terbaru yang perlu diverifikasi.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
