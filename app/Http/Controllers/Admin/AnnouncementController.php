<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RouteHelpers;
use App\Helpers\WhatsAppHelper;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    /**
     * Display announcements and expiring members list.
     */
    public function index(): View|RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $today            = Carbon::today();
        $sevenDaysFromNow = $today->copy()->addDays(7);

        $announcements = Announcement::query()
            ->where('status', '!=', 'archived')
            ->latest('publish_at')
            ->latest()
            ->get();

        $archivedAnnouncements = Announcement::query()
            ->where('status', 'archived')
            ->latest('archived_at')
            ->get();

        $expiringMembers = Member::query()
            ->whereNotNull('expires_at')
            ->whereDate('expires_at', '>', $today)
            ->whereDate('expires_at', '<=', $sevenDaysFromNow)
            ->orderBy('expires_at')
            ->orderBy('full_name')
            ->get();

        return view('admin.announcements', array_merge(RouteHelpers::pageMeta('announcements'), [
            'announcements'         => $announcements,
            'archivedAnnouncements' => $archivedAnnouncements,
            'expiringMembers'       => $expiringMembers,
            'whatsAppChannelUrl'    => config('services.whatsapp.channel_url'),
            'whatsAppChannelShare'  => session('whatsapp_channel_share'),
            'whatsAppDispatch'      => session('whatsapp_dispatch'),
        ]));
    }

    /**
     * Publish a new announcement.
     */
    public function publish(Request $request): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['required', 'string'],
        ]);

        $announcement = Announcement::create([
            'title'      => $validated['title'],
            'body'       => $validated['body'],
            'status'     => 'published',
            'publish_at' => now(),
        ]);

        $message = WhatsAppHelper::announcementMessage($announcement->title, $announcement->body);
        $channelUrl = config('services.whatsapp.channel_url');
        $statusMessage = 'Pengumuman baru berhasil dipublikasikan. Lanjutkan posting lewat WhatsApp Share.';

        return back()
            ->with('status', $statusMessage)
            ->with('whatsapp_channel_share', [
                'title'     => $announcement->title,
                'message'   => $message,
                'url'       => $channelUrl,
                'share_url' => 'https://wa.me/?text=' . rawurlencode($message),
                'auto_open' => true,
            ]);
    }

    /**
     * Archive an announcement.
     */
    public function archive(Request $request): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $validated = $request->validate([
            'announcement_id' => ['required', 'exists:announcements,id'],
        ]);

        Announcement::query()
            ->whereKey($validated['announcement_id'])
            ->update([
                'status'      => 'archived',
                'archived_at' => now(),
            ]);

        return back()->with('status', 'Pengumuman berhasil dipindahkan ke arsip.');
    }

    /**
     * Restore an announcement from archive.
     */
    public function restore(Request $request): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $validated = $request->validate([
            'announcement_id' => ['required', 'exists:announcements,id'],
        ]);

        Announcement::query()
            ->whereKey($validated['announcement_id'])
            ->update([
                'status'      => 'published',
                'archived_at' => null,
            ]);

        return back()->with('status', 'Pengumuman berhasil dipulihkan dari arsip.');
    }

    /**
     * Delete an announcement permanently.
     */
    public function delete(Request $request): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $validated = $request->validate([
            'announcement_id' => ['required', 'exists:announcements,id'],
        ]);

        Announcement::query()
            ->whereKey($validated['announcement_id'])
            ->delete();

        return back()->with('status', 'Pengumuman berhasil dihapus.');
    }

    /**
     * Send expiration reminder for a member.
     */
    public function sendReminder(Request $request): RedirectResponse
    {
        if ($redirect = RouteHelpers::ensureAdmin()) {
            return $redirect;
        }

        $memberId = $request->input('gym_member_id') ?? $request->input('member_id');
        $member = Member::query()->findOrFail($memberId);

        $daysLeft = $member->expires_at
            ? (int) Carbon::today()->diffInDays($member->expires_at->copy()->startOfDay(), false)
            : null;

        if ($daysLeft === null || $daysLeft < 0 || $daysLeft > 7) {
            return back()->withErrors(['gym_member_id' => 'Pengingat hanya bisa dikirim untuk member yang masa aktifnya tersisa 0 sampai 7 hari.']);
        }

        $message = "Halo {$member->full_name}, masa aktif membership Anda akan segera berakhir pada " . ($member->expires_at ? $member->expires_at->format('d M Y') : '-') . ". Silakan datang ke kasir untuk melakukan perpanjangan membership.";

        // Update last reminder timestamp
        $member->update(['last_membership_reminder_at' => now()]);

        // Prepare announcement record
        Announcement::create([
            'title'      => 'Pengingat Membership',
            'body'       => "[TARGET_MEMBER_ID:{$member->id}] {$message}",
            'status'     => 'published',
            'publish_at' => now(),
        ]);

        $statusMessage = "Pengingat perpanjangan untuk {$member->full_name} berhasil dikirim ke halaman member.";

        return back()->with('status', $statusMessage);
    }
}
