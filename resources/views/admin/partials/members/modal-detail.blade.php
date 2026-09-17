@php
    $memberCheckins = $member->relationLoaded('checkinHistories')
        ? ($member->checkinHistories->isNotEmpty()
            ? $member->checkinHistories
            : ($member->relationLoaded('verifiedCheckins') ? $member->verifiedCheckins : collect()))
        : ($member->relationLoaded('verifiedCheckins') ? $member->verifiedCheckins : collect());
    $memberProductTransactions = $member->relationLoaded('productPurchaseHistories')
        ? $member->productPurchaseHistories
        : ($member->relationLoaded('productTransactions') ? $member->productTransactions : collect());
@endphp

        <div class="modal fade" id="detailMemberModal{{ $member->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content bg-dark text-white border-0 shadow-lg" style="border-radius: 1.5rem;">
                    <div class="modal-header border-bottom border-white border-opacity-10 p-4">
                        <h5 class="modal-title fw-bold">Detail Member</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 text-center">
                        @if ($member->profile_photo_url)
                            <img src="{{ $member->profile_photo_url }}"
                                class="rounded-circle mb-3 border border-3 border-danger"
                                style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="member-photo-placeholder mx-auto mb-3"
                                style="width: 100px; height: 100px; font-size: 2rem;">
                                {{ strtoupper(substr($member->full_name, 0, 1)) }}</div>
                        @endif
                        <h4 class="fw-bold mb-0">{{ $member->full_name }}</h4>
                        <p class="opacity-50 small mb-4">{{ $member->email ?: 'Email tidak tersedia' }}</p>

                        <div class="row g-3 text-start bg-white bg-opacity-10 p-4 rounded-4 mb-4">
                            <div class="col-6"><label
                                    class="small d-block text-uppercase fw-bold opacity-50">Telepon</label>
                                <span class="fw-bold">{{ $member->phone ?: '-' }}</span>
                            </div>
                            <div class="col-6"><label class="small d-block text-uppercase fw-bold opacity-50">Metode
                                    Bayar</label>
                                <span class="badge bg-danger px-3">{{ $member->payment_method ?: 'Cash' }}</span>
                            </div>
                            <div class="col-6"><label class="small d-block text-uppercase fw-bold opacity-50">Tgl
                                    Daftar</label>
                                <span class="fw-bold">{{ $member->joined_at?->format('d M Y') }}</span>
                            </div>
                            <div class="col-6"><label class="small d-block text-uppercase fw-bold opacity-50">Masa
                                    Aktif</label>
                                <span class="fw-bold text-danger">{{ $member->expires_at?->format('d M Y') }}</span>
                            </div>
                        </div>

                        <div class="row g-3 text-start mb-4">
                            <div class="col-md-6">
                                <div class="bg-white bg-opacity-10 p-4 rounded-4 h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <div class="small text-uppercase fw-bold opacity-50">Riwayat Check-in</div>
                                            <div class="fw-bold">{{ $member->checkins_count ?? 0 }} kali check-in</div>
                                        </div>
                                        <i class="fas fa-calendar-check text-danger fs-4"></i>
                                    </div>

                                    @forelse ($memberCheckins->take(5) as $checkin)
                                        <div class="d-flex justify-content-between gap-3 py-2 border-top border-white border-opacity-10">
                                            <span class="small opacity-75">Tanggal</span>
                                            <span class="small fw-bold text-end">
                                                {{ ($checkin->occurred_at ?? $checkin->checked_in_at)?->format('d M Y, H:i') ?: '-' }}
                                            </span>
                                        </div>
                                    @empty
                                        <div class="small opacity-50 border-top border-white border-opacity-10 pt-3">
                                            Belum ada riwayat check-in.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="bg-white bg-opacity-10 p-4 rounded-4 h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <div class="small text-uppercase fw-bold opacity-50">Riwayat Pembelian</div>
                                            <div class="fw-bold">{{ $member->product_transactions_count ?? 0 }} transaksi barang</div>
                                        </div>
                                        <i class="fas fa-shopping-bag text-danger fs-4"></i>
                                    </div>

                                    @forelse ($memberProductTransactions->take(5) as $transaction)
                                        <div class="py-2 border-top border-white border-opacity-10">
                                            <div class="d-flex justify-content-between gap-3">
                                                <span class="small fw-bold">
                                                    {{ $transaction->product?->name ?? $transaction->title ?? $transaction->transaction_type ?? 'Barang' }}
                                                </span>
                                                <span class="small opacity-75 text-end">
                                                    {{ ($transaction->occurred_at ?? $transaction->transaction_at)?->format('d M Y') ?: '-' }}
                                                </span>
                                            </div>
                                            <div class="small opacity-50">
                                                {{ $transaction->quantity ?? 1 }} item · Rp{{ number_format($transaction->amount ?? 0, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @empty
                                        <div class="small opacity-50 border-top border-white border-opacity-10 pt-3">
                                            Belum ada riwayat pembelian barang.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- QR Code --}}
                        <div class="mb-3">
                            <div class="small text-uppercase fw-bold opacity-50 mb-2">QR Code Member</div>
                            <div id="qr-{{ $member->id }}"
                                style="display:inline-block; background:#fff; padding:12px; border-radius:12px;">
                            </div>
                            <div class="small opacity-50 mt-2">{{ $member->checkin_code }}</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button class="btn btn-danger w-100 rounded-pill fw-bold py-3"
                            onclick="printMemberCard(
                        '{{ $member->full_name }}',
                        '{{ $member->checkin_code }}',
                        '{{ $member->expires_at?->format('d M Y') }}',
                        '{{ $member->phone }}',
                        'qr-{{ $member->id }}'
                    )">
                            <i class="fas fa-print me-2"></i> Cetak Kartu Member
                        </button>
                    </div>
                </div>
            </div>
        </div>
