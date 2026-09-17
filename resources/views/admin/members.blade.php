@extends('admin.layout')

@section('content')
    @php
        $isExpiredSection = $memberSection === 'expired';
        $paymentMethods = ['Cash', 'Transfer Bank', 'QRIS', 'Debit Card'];
    @endphp


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-members.css') }}">
@endpush


    <div class="dashboard-page">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <div class="ds-section-label">Arena Gym · Management Member</div>
                <h1 class="dashboard-title">Manajemen Member</h1>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success border-0 bg-success text-white rounded-3 mb-4 py-2 small shadow-sm">
                <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
            </div>
        @endif

        <div class="member-summary-grid">
            <div class="member-summary-card">
                <div class="summary-label">Aktif</div>
                <div class="summary-value text-success">{{ $totalActiveCount }}</div>
                <div class="summary-note">Member Aktif</div>
            </div>
            <div class="member-summary-card">
                <div class="summary-label">Expired</div>
                <div class="summary-value text-danger">{{ $totalExpiredCount }}</div>
                <div class="summary-note">Member Expired</div>
            </div>
            <div class="member-summary-card">
                <div class="summary-label">Segera Habis</div>
                <div class="summary-value text-warning">{{ $expiringSoonCount }}</div>
                <div class="summary-note">Member Segera Habis</div>
            </div>
            <div class="member-summary-card">
                <div class="summary-label">Total Data</div>
                <div class="summary-value text-white">{{ $totalMembersCount }}</div>
                <div class="summary-note">Total Data Member</div>
            </div>
        </div>

        <div class="panel-card">
            {{-- Tab buttons + Search --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4 panel-tabs-row">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.members', ['section' => 'active']) }}"
                        class="btn btn-sm rounded-pill {{ $isExpiredSection ? 'btn-outline-secondary' : 'btn-light text-dark fw-bold' }}">Member
                        Aktif</a>
                    <a href="{{ route('admin.members', ['section' => 'expired']) }}"
                        class="btn btn-sm rounded-pill {{ $isExpiredSection ? 'btn-light text-dark fw-bold' : 'btn-outline-secondary' }}">Member
                        Expired</a>
                </div>
                <div class="member-search-wrap">
                    <i class="fas fa-search member-search-icon"></i>
                    <input type="text" id="memberSearchInput" class="member-search-input"
                        placeholder="Cari nama atau no. HP...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle member-table">
                    <thead>
                        <tr>
                            <th style="width: 70px">Foto</th>
                            <th>Member</th>
                            <th>Telepon</th>
                            <th>Tgl Daftar</th>
                            <th>Metode Bayar</th>
                            <th>Masa Aktif</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="memberTableBody">
                        @forelse ($currentItems as $member)
                            @php
                                $diff = $member->expires_at ? now()->diffInDays($member->expires_at, false) : null;
                                $daysLeft = $diff !== null ? ceil($diff) : null;
                                $needsRenewalReminder = $daysLeft !== null && $daysLeft >= 0 && $daysLeft <= 7;
                            @endphp
                            <tr class="member-row {{ $needsRenewalReminder ? 'member-row-warning' : '' }}" data-name="{{ strtolower($member->full_name) }}"
                                data-phone="{{ $member->phone }}">
                                <td>
                                    @if ($member->profile_photo_url)
                                        <img src="{{ $member->profile_photo_url }}"
                                            class="member-photo"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="member-photo-placeholder" style="display: none;">
                                            {{ strtoupper(substr($member->full_name, 0, 1)) }}</div>
                                    @else
                                        <div class="member-photo-placeholder">
                                            {{ strtoupper(substr($member->full_name, 0, 1)) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $member->full_name }}</div>
                                    <div class="small opacity-50">{{ $member->email ?: 'Tanpa Email' }}</div>
                                </td>
                                <td class="small">{{ $member->phone ?: '-' }}</td>
                                <td class="small">{{ $member->joined_at?->format('d M Y') ?: '-' }}</td>
                                <td><span
                                        class="badge bg-white bg-opacity-10 text-white fw-normal">{{ $member->payment_method ?: 'Cash' }}</span>
                                </td>
                                <td class="small">
                                    <div
                                        class="fw-bold {{ $isExpiredSection ? 'text-danger' : ($daysLeft <= 7 ? 'text-warning' : 'text-white') }}">
                                        Hingga: {{ $member->expires_at?->format('d M Y') }}
                                    </div>
                                    @if ($needsRenewalReminder)
                                        <div class="member-expiry-warning">
                                            <i class="fas fa-clock"></i>
                                            {{ $daysLeft === 0 ? 'Hari ini' : 'H-' . $daysLeft }}
                                        </div>
                                        <div class="small opacity-50 mt-1">
                                            Diingatkan:
                                            {{ $member->last_membership_reminder_at ? $member->last_membership_reminder_at->format('d M Y, H:i') : 'Belum' }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        @if ($needsRenewalReminder)
                                            <form method="POST" action="{{ route('admin.announcements.reminders.send') }}"
                                                class="d-inline">
                                                @csrf
                                                <input type="hidden" name="gym_member_id" value="{{ $member->id }}">
                                                <button type="submit" class="btn-action {{ $member->last_membership_reminder_at ? 'btn-action-reminder-sent' : 'btn-action-reminder-pending' }}"
                                                    title="Ingatkan untuk perpanjang"
                                                    onclick="return confirm('Kirim pengingat perpanjangan untuk {{ $member->full_name }}?')">
                                                    <i class="fas fa-bell"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <button class="btn-action {{ $needsRenewalReminder ? 'btn-action-warning' : 'btn-action-success' }}" data-bs-toggle="modal"
                                            data-bs-target="#renewMemberModal{{ $member->id }}"><i
                                                class="fas fa-sync-alt"></i></button>
                                        <button class="btn-action btn-action-info" data-bs-toggle="modal"
                                            data-bs-target="#detailMemberModal{{ $member->id }}"><i
                                                class="fas fa-eye"></i></button>
                                        <button class="btn-action btn-action-warning" data-bs-toggle="modal"
                                            data-bs-target="#editMemberModal{{ $member->id }}"><i
                                                class="fas fa-edit"></i></button>
                                        <form action="{{ route('admin.members.destroy', $member) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-danger"
                                                onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-secondary">Data Kosong</td>
                            </tr>
                        @endforelse
                        {{-- Row muncul saat hasil search kosong --}}
                        <tr id="memberNoResult">
                            <td colspan="7" class="text-center py-5 text-secondary">
                                <i class="fas fa-search me-2 opacity-50"></i>Member tidak ditemukan
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    @include('admin.partials.members.modal-add')

    @foreach ($currentItems as $member)
        @include('admin.partials.members.modal-detail')
        @include('admin.partials.members.modal-edit')
        @include('admin.partials.members.modal-renew')
    @endforeach

    <div class="mt-4">
        <div class="d-flex flex-column align-items-center gap-3">
            {{-- Tombol Navigasi --}}
            <div class="custom-pagination">
                @if (isset($currentItems) && method_exists($currentItems, 'links'))
                    {{-- Kita sembunyikan info bawaan lewat CSS di atas --}}
                    {{ $currentItems->links('pagination::bootstrap-5') }}
                @endif
            </div>

            {{-- Keterangan Data Manual (Yang ini tetap dipertahankan) --}}
            <div class="small text-white-50">
                @if (isset($currentItems) && method_exists($currentItems, 'total'))
                    Menampilkan {{ $currentItems->firstItem() ?: 0 }} - {{ $currentItems->lastItem() ?: 0 }}
                    dari {{ $currentItems->total() }} member
                @elseif (isset($currentItems))
                    Menampilkan {{ $currentItems->count() }} member
                @endif
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/qrcode.min.js') }}"></script>
    <script src="{{ asset('js/admin-members.js') }}"></script>
@endpush
