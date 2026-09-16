<?php

namespace Tests\Feature;

use App\Models\Checkin;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashierQrCheckinTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_checkin_page_shows_member_qr_entry_point(): void
    {
        $this->withSession([
            'auth' => [
                'role' => 'cashier',
                'login' => 'cashier',
            ],
        ])->get(route('cashier.checkins'))
            ->assertOk()
            ->assertSee('Manual')
            ->assertSee('Scan QR');
    }

    public function test_member_can_submit_self_service_qr_checkin(): void
    {
        $member = Member::query()->create([
            'full_name' => 'Alya Fitri',
            'phone' => '08123456789',
            'joined_at' => now()->subDays(7),
            'expires_at' => now()->addDays(23),
            'checkin_code' => 'AGM-SELFTEST01',
        ]);

        $this->post(route('member.checkin.store'), [
            'submitted_name' => 'Alya Fitri',
            'submitted_phone' => '08123456789',
            'notes' => 'Check-in dari QR kasir',
        ])->assertRedirect(route('member.checkin'));

        $this->assertDatabaseHas('checkins', [
            'member_id' => $member->id,
            'checkin_method' => 'qr_member',
            'verification_status' => 'pending',
            'submitted_name' => 'Alya Fitri',
            'submitted_phone' => '08123456789',
            'notes' => 'Check-in dari QR kasir',
        ]);

        $this->assertSame(1, Checkin::query()->count());

        $checkin = Checkin::query()->firstOrFail();

        $this->withSession([
            'auth' => [
                'role' => 'cashier',
                'login' => 'cashier',
            ],
        ])->post(route('cashier.checkins.verify', $checkin))
            ->assertRedirect(route('cashier.checkins'));

        $this->assertDatabaseHas('checkins', [
            'id' => $checkin->id,
            'verification_status' => 'verified',
        ]);
    }

    public function test_cashier_can_assist_member_checkin_without_hp(): void
    {
        $member = Member::query()->create([
            'full_name' => 'Rina Kusuma',
            'phone' => '082212345678',
            'joined_at' => now()->subDays(10),
            'expires_at' => now()->addDays(20),
            'checkin_code' => 'AGM-SELFTEST02',
        ]);

        $this->withSession([
            'auth' => [
                'role' => 'cashier',
                'login' => 'cashier',
            ],
        ])->post(route('cashier.checkins.store'), [
            'member_id' => $member->id,
        ])->assertRedirect(route('cashier.checkins'));

        $this->assertDatabaseHas('checkins', [
            'member_id' => $member->id,
            'checkin_method' => 'cashier',
            'verification_status' => 'verified',
        ]);
    }
}
