@php
    $renewDiff = $member->expires_at ? now()->diffInDays($member->expires_at, false) : null;
    $renewDaysLeft = $renewDiff !== null ? ceil($renewDiff) : null;
    $renewNeedsWarning = $renewDaysLeft !== null && $renewDaysLeft >= 0 && $renewDaysLeft <= 7;
    $renewAccentText = $renewNeedsWarning ? 'text-warning' : 'text-success';
    $renewAccentBg = $renewNeedsWarning ? 'bg-warning' : 'bg-success';
    $renewAccentBorder = $renewNeedsWarning ? 'border-warning' : 'border-success';
    $renewButtonClass = $renewNeedsWarning ? 'btn-warning text-dark' : 'btn-success';
@endphp

            <div class="modal fade" id="renewMemberModal{{ $member->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content bg-dark text-white border-0 shadow-lg" style="border-radius: 1.5rem;">
                        <div class="modal-header border-bottom border-white border-opacity-10 p-4">
                            <h5 class="modal-title fw-bold {{ $renewAccentText }}">
                                <i class="fas fa-history me-2"></i>Perpanjang Member
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('admin.members.update', $member) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="modal-body p-4">

                                <div class="p-3 mb-4"
                                    style="background: rgba(255,255,255,0.03); border-radius: 1rem; border: 1px solid rgba(255,255,255,0.08);">
                                    <div class="row g-3">
                                        <div class="col-7">
                                            <label class="d-block small text-uppercase fw-bold opacity-50 mb-1"
                                                style="font-size: 0.65rem; letter-spacing: 0.5px;">Nama Member</label>
                                            <div class="fw-bold text-white fs-6">{{ $member->full_name }}</div>
                                        </div>
                                        <div class="col-5 text-end">
                                            <label class="d-block small text-uppercase fw-bold opacity-50 mb-1"
                                                style="font-size: 0.65rem; letter-spacing: 0.5px;">Status Saat Ini</label>
                                            <div>
                                                @if ($member->expires_at && $member->expires_at->isPast())
                                                    <span
                                                        class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1 rounded-pill"
                                                        style="font-size: 0.7rem;">Expired</span>
                                                @elseif ($renewNeedsWarning)
                                                    <span
                                                        class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-1 rounded-pill"
                                                        style="font-size: 0.7rem;">
                                                        {{ $renewDaysLeft === 0 ? 'Hari Ini' : 'H-' . $renewDaysLeft }}
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1 rounded-pill"
                                                        style="font-size: 0.7rem;">Aktif</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3 pt-3 border-top border-white border-opacity-10">
                                            <label class="d-block small text-uppercase fw-bold opacity-50 mb-1"
                                                style="font-size: 0.65rem; letter-spacing: 0.5px;">Masa Aktif
                                                Sebelumnya</label>
                                            <div class="text-white-50 fw-semibold">
                                                {{ $member->expires_at?->format('d M Y') ?: '-' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-uppercase opacity-50 mb-2">Durasi
                                        Perpanjangan</label>
                                    {{-- Kita buat select ini read-only atau hanya satu pilihan --}}
                                    <select name="duration"
                                        class="form-select bg-white bg-opacity-10 border-0 text-white p-3 shadow-none"
                                        style="border-radius: 0.8rem;"
                                        onchange="updateRenewPreview(this, '{{ $member->id }}', '{{ $member->expires_at ? $member->expires_at->format('Y-m-d') : now()->format('Y-m-d') }}')">
                                        <option value="1" selected class="text-dark">1 Bulan</option>
                                    </select>
                                </div>

                                <div
                                    class="p-3 rounded-4 {{ $renewAccentBg }} bg-opacity-10 border {{ $renewAccentBorder }} border-opacity-25 mt-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="small {{ $renewAccentText }} fw-bold opacity-75">Masa Aktif Baru (+<span
                                                    id="prevMonths{{ $member->id }}">1</span> Bln)</div>
                                            <div id="prevNew{{ $member->id }}" class="fw-bold {{ $renewAccentText }} fs-5 mt-1">
                                                {{-- Inisialisasi tampilan awal: Masa Aktif Sekarang + 1 Bulan --}}
                                                @php
                                                    $baseDate =
                                                        $member->expires_at && $member->expires_at->isFuture()
                                                            ? $member->expires_at
                                                            : now();
                                                    echo $baseDate->addMonth()->format('d M Y');
                                                @endphp
                                            </div>
                                        </div>
                                        <div class="{{ $renewAccentBg }} text-white rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 45px; height: 45px;">
                                            <i class="fas fa-calendar-plus"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer border-0 p-4 pt-0">
                                <input type="hidden" name="full_name" value="{{ $member->full_name }}">
                                <input type="hidden" name="payment_method" value="{{ $member->payment_method }}">

                                <button type="submit" class="btn {{ $renewButtonClass }} w-100 rounded-pill fw-bold py-3 shadow-sm">
                                    Konfirmasi & Perbarui Member
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
