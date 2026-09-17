<?php

namespace App\Http\Controllers\Cashier;

use App\Helpers\RouteHelpers;
use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckinController extends Controller
{
    /**
     * Display cashier check-in page and activity feed.
     */
    public function index(): View|RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureCashier()) {
            return $redirect;
        }

        return view('cashier.checkins', RouteHelpers::buildCashierViewData([
            'pageTitle'          => 'Check-in - Kasir Arena Gym',
            'activePage'         => 'cashier.checkins',
            'sidebarStatusTitle' => 'Check-in Kasir',
            'sidebarStatusNote'  => 'Bantu member check-in dan pantau daftar check-in hari ini.',
        ]));
    }

    /**
     * Look up member data by checkin code (AJAX).
     */
    public function lookupMember(Request $request): JsonResponse
    {
        if ($redirect = RouteHelpers::ensureCashier()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'checkin_code' => ['required', 'string', 'max:40'],
        ]);

        $checkinCode = strtoupper(trim($validated['checkin_code']));
        $member = Member::query()
            ->where('checkin_code', $checkinCode)
            ->first();

        if (! $member) {
            return response()->json(['message' => 'Member tidak ditemukan.'], 404);
        }

        if (! $member->expires_at || $member->expires_at->lt(now()->startOfDay())) {
            return response()->json(['message' => 'Membership member ini sudah expired.'], 422);
        }

        return response()->json([
            'member' => [
                'id'                => $member->id,
                'full_name'         => $member->full_name,
                'phone'             => $member->phone ?: '-',
                'checkin_code'      => $member->checkin_code,
                'expires_at'        => $member->expires_at?->format('d M Y') ?: '-',
                'profile_photo_url' => $member->profile_photo_url,
                'profile_initials'  => $member->profile_initials,
            ],
        ]);
    }

    /**
     * Store member check-in by cashier or QR member.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureCashier()) {
            return $redirect;
        }

        $actor = in_array($request->input('actor'), ['cashier', 'qr_member'], true)
            ? $request->input('actor')
            : 'cashier';

        $redirectParams = $actor === 'qr_member' ? ['section' => 'qr'] : [];

        return RouteHelpers::storeMemberCheckin(
            request: $request,
            actor: $actor,
            redirectRoute: 'cashier.checkins',
            redirectParams: $redirectParams,
        );
    }

    /**
     * Verify pending check-in from QR self-service.
     */
    public function verify(Checkin $checkin): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureCashier()) {
            return $redirect;
        }

        if ($checkin->verification_status !== 'pending') {
            return redirect()->route('cashier.checkins')->with('status', 'Pengajuan check-in ini sudah diproses.');
        }

        $checkin->update([
            'verification_status' => 'verified',
            'checkin_method'      => 'qr_member',
            'verified_at'         => now(),
            'verified_by'         => RouteHelpers::authUserId(),
        ]);

        return redirect()->route('cashier.checkins')
            ->with('status', "Check-in {$checkin->member?->full_name} berhasil divalidasi kasir.");
    }

    /**
     * Reject pending check-in.
     */
    public function reject(Checkin $checkin): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureCashier()) {
            return $redirect;
        }

        if ($checkin->verification_status !== 'pending') {
            return redirect()->route('cashier.checkins')->with('status', 'Pengajuan check-in ini sudah diproses.');
        }

        $checkin->update([
            'verification_status' => 'rejected',
            'verified_at'         => now(),
            'verified_by'         => RouteHelpers::authUserId(),
        ]);

        return redirect()->route('cashier.checkins')
            ->with('status', "Pengajuan check-in {$checkin->member?->full_name} ditolak.");
    }
}
