<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RouteHelpers;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\ProfilePhotoChangeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProfilePhotoRequestController extends Controller
{
    /**
     * Display a listing of profile photo change requests.
     */
    public function index(): View|RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        return view('admin.profile-photo-requests', [
            'pageTitle'  => 'Persetujuan Foto Profil - Admin Arena Gym',
            'activePage' => 'profile-photo-requests',
            'requests'   => ProfilePhotoChangeRequest::query()
                ->with('member')
                ->latest()
                ->paginate(12),
        ]);
    }

    /**
     * Serve the requested photo file safely.
     */
    public function showPhoto(ProfilePhotoChangeRequest $photoRequest): BinaryFileResponse|RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $path = storage_path('app/public/' . $photoRequest->requested_photo_path);

        abort_unless(is_file($path), 404);

        return response()->file($path);
    }

    /**
     * Approve a photo change request.
     */
    public function approve(ProfilePhotoChangeRequest $photoRequest): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        if ($photoRequest->status !== 'pending') {
            return redirect()->route('admin.profile-photo-requests')->with('status', 'Permintaan ini sudah diproses.');
        }

        $member = $photoRequest->member;

        if (! $member) {
            return redirect()->route('admin.profile-photo-requests')->withErrors(['member' => 'Data member tidak ditemukan.']);
        }

        if ($member->profile_photo_path) {
            Storage::disk('public')->delete($member->profile_photo_path);
        }

        $member->update([
            'profile_photo_path'         => $photoRequest->requested_photo_path,
            'profile_photo_change_count' => (int) ($member->profile_photo_change_count ?? 3) + 1,
        ]);

        $photoRequest->update([
            'status'      => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        Announcement::create([
            'title'      => 'Foto Profil Disetujui',
            'body'       => "[TARGET_MEMBER_ID:{$member->id}] Pengajuan ganti foto profil Anda telah disetujui oleh admin.",
            'status'     => 'published',
            'publish_at' => now(),
        ]);

        return redirect()->route('admin.profile-photo-requests')->with('status', 'Foto profil member berhasil disetujui dan diganti.');
    }

    /**
     * Reject a photo change request.
     */
    public function reject(ProfilePhotoChangeRequest $photoRequest): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        if ($photoRequest->status === 'pending') {
            $photoRequest->update([
                'status'      => 'rejected',
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ]);
        }

        return redirect()->route('admin.profile-photo-requests')->with('status', 'Permintaan ganti foto profil ditolak.');
    }
}
