<?php

namespace App\Http\Controllers\Cashier;

use App\Helpers\RouteHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display cashier dashboard and POS interface.
     */
    public function index(): View|RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureCashier()) {
            return $redirect;
        }

        return view('cashier.dashboard_home', RouteHelpers::buildCashierViewData());
    }
}
