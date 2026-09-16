<div class="card cashier-validation-card p-4 h-100">
    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-3">
        <div>
            <div class="section-label">Data</div>
            <h2 class="h4 fw-bold mt-2 mb-0">Semua Check-in Hari Ini</h2>
        </div>
        <span class="status-badge badge-soft-teal">{{ $allEntries->count() }} masuk</span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Kode / Status</th>
                    <th>Metode</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($allEntries as $record)
                    @php
                        $member = $record->member;
                        $statusLabel = $record->verification_status === 'pending' ? 'Menunggu' : 'Diverifikasi';
                        $statusClass = $record->verification_status === 'pending' ? 'warning' : 'success';
                        $methodLabel = $record->checkin_method === 'cashier' ? 'Manual' : 'QR Scan';
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if ($member?->profile_photo_url)
                                    <a href="{{ $member->profile_photo_url }}" target="_blank" title="Lihat Foto Penuh"><img src="{{ $member->profile_photo_url }}" alt="Foto {{ $member->full_name }}" class="table-avatar" style="cursor: pointer;"></a>
                                @elseif ($member)
                                    <span class="table-avatar-placeholder">{{ $member->profile_initials }}</span>
                                @else
                                    <span class="table-avatar-placeholder">-</span>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $member?->full_name ?? ($record->submitted_name ?: '-') }}</div>
                                    <div class="small muted-copy">{{ $record->submitted_phone ?: ($member?->phone ?? '-') }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $member?->checkin_code ?? '-' }}</div>
                            <span class="badge text-bg-{{ $statusClass }} mt-1">{{ $statusLabel }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $methodLabel }}</span>
                        </td>
                        <td>{{ $record->checked_in_at->format('H:i') }}</td>
                        <td>
                            @if ($record->verification_status === 'pending')
                                <div class="d-flex gap-2 flex-wrap">
                                    <form method="POST" action="{{ route('cashier.checkins.verify', $record) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill">Validasi</button>
                                    </form>
                                    <form method="POST" action="{{ route('cashier.checkins.reject', $record) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Tolak</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-secondary">Belum ada check-in hari ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
