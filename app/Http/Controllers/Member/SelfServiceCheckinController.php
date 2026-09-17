<?php

namespace App\Http\Controllers\Member;

use App\Helpers\RouteHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SelfServiceCheckinController extends Controller
{
    /**
     * Display member self-service QR checkin page.
     */
    public function show(): View
    {
        return view('checkin.member-self-service', [
            'pageTitle' => 'Check-in Member Arena Gym',
        ]);
    }

    /**
     * Process member self-service QR checkin.
     */
    public function store(Request $request): RedirectResponse
    {
        return RouteHelpers::storeMemberCheckin(
            request: $request,
            actor: 'qr_member',
            redirectRoute: 'member.checkin'
        );
    }
}
