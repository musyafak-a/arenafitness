<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RouteHelpers;
use App\Http\Controllers\Controller;
use App\Models\MemberFeedback;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    /**
     * Display member feedbacks list.
     */
    public function index(): View|RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $feedbacks = MemberFeedback::query()
            ->latest()
            ->paginate(15);

        $unreadCount = MemberFeedback::query()->whereNull('read_at')->count();

        return view('admin.feedbacks', array_merge(RouteHelpers::pageMeta('dashboard'), [
            'pageTitle'   => 'Kritik & Saran - Arena Gym',
            'activePage'  => 'feedbacks',
            'feedbacks'   => $feedbacks,
            'unreadCount' => $unreadCount,
        ]));
    }

    /**
     * Mark feedback as read.
     */
    public function markAsRead(Request $request, MemberFeedback $feedback): JsonResponse|RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $feedback->update(['read_at' => now()]);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('status', 'Pesan ditandai sudah dibaca.');
    }

    /**
     * Delete a feedback record.
     */
    public function destroy(MemberFeedback $feedback): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $feedback->delete();

        return back()->with('status', 'Kritik dan saran berhasil dihapus.');
    }
}
