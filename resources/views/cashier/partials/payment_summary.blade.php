                <div class="cashier-panel elevated">
                    <div class="cashier-panel-header">
                        <div class="cashier-panel-title">
                            <span class="cashier-panel-title-icon"><i class="fas fa-wallet"></i></span>
                            <div>
                                <div class="section-label">Metode Bayar</div>
                                <h2 class="h5 fw-bold mt-2 mb-0">Ringkasan</h2>
                            </div>
                        </div>
                    </div>
                    <div class="cashier-panel-body">
                        @forelse ($paymentMethods as $item)
                            <div class="method-row">
                                <div class="d-flex justify-content-between small mb-2">
                                    <span class="muted-copy fw-semibold">{{ $item['label'] }}</span>
                                    <span class="fw-bold">{{ $item['value'] }}</span>
                                </div>
                                <div class="mini-progress">
                                    <div class="mini-progress-bar bg-{{ $item['color'] }}" style="width: {{ $item['progress'] }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="cashier-empty">Belum ada pembayaran lunas.</div>
                        @endforelse
                    </div>
                </div>
