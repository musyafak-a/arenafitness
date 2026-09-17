<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RouteHelpers;
use App\Http\Controllers\Controller;
use App\Models\DailyGuest;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DailyPassController extends Controller
{
    /**
     * Display a listing of daily pass guests.
     */
    public function index(Request $request): View|RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $search = trim($request->string('q')->value());

        $query = DailyGuest::query()->with('transactions')->latest('visit_at');

        if ($search !== '') {
            $query->where('full_name', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
        }

        $dailyPasses = $query->paginate(10);

        return view('admin.daily_passes', array_merge(RouteHelpers::pageMeta('daily-passes'), [
            'dailyPasses' => $dailyPasses,
            'search' => $search,
        ]));
    }

    /**
     * Store a new daily pass visit.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $validated = $request->validate([
            'full_name'      => ['required', 'string', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:30'],
            'payment_method' => ['required', 'string', 'max:30'],
            'payment_amount' => ['nullable', 'integer', 'min:1'],
            'notes'          => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            $guest = DailyGuest::query()->create([
                'full_name'  => $validated['full_name'],
                'phone'      => $validated['phone'] ?? null,
                'visit_type' => 'daily_pass',
                'visit_at'   => now(),
            ]);

            Transaction::query()->create([
                'invoice'          => 'INV-' . time() . '-' . rand(100, 999),
                'daily_guest_id'   => $guest->id,
                'type'             => 'daily_pass',
                'amount'           => (int) ($validated['payment_amount'] ?? 30000),
                'paid_amount'      => (int) ($validated['payment_amount'] ?? 30000),
                'payment_method'   => $validated['payment_method'],
                'payment_status'   => 'verified',
                'transaction_at'   => now(),
                'cashier_user_id'  => RouteHelpers::authUserId(),
            ]);
        });

        return redirect()->route('admin.daily-passes')->with('status', 'Data kunjungan daily pass berhasil ditambahkan.');
    }

    /**
     * Delete a daily pass visit.
     */
    public function destroy(DailyGuest $dailyPass): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $dailyPass->delete();

        return redirect()->route('admin.daily-passes')->with('status', 'Data kunjungan daily pass berhasil dihapus.');
    }
}
