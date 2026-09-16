<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\MembershipPlan;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CashierMemberPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_renewal_before_expiry_keeps_the_same_membership_cycle_day(): void
    {
        Carbon::setTestNow('2026-05-20 09:00:00');

        MembershipPlan::query()->create([
            'name' => 'Bulanan',
            'duration_months' => 1,
            'price' => 90000,
        ]);

        $member = Member::query()->create([
            'full_name' => 'Member Tanggal 26',
            'joined_at' => '2026-04-26',
            'expires_at' => '2026-05-26',
        ]);

        $this->withSession([
            'auth' => [
                'role' => 'cashier',
                'login' => 'kasir',
            ],
        ])->post(route('cashier.member-payments.store'), [
            'member_id' => $member->id,
            'amount' => 90000,
            'paid_amount' => 100000,
            'payment_method' => 'cash',
            'notes' => 'Renew lebih awal',
        ])->assertRedirect(route('cashier.transactions', ['section' => 'member']));

        $member->refresh();

        $this->assertSame('2026-06-26', $member->expires_at?->toDateString());

        $this->assertDatabaseHas('transactions', [
            'member_id' => $member->id,
            'type' => Transaction::TYPE_MEMBERSHIP,
            'amount' => 90000,
            'paid_amount' => 100000,
            'change_amount' => 10000,
            'payment_status' => 'verified',
        ]);

        Carbon::setTestNow();
    }

    public function test_pending_member_payment_verification_uses_the_same_cycle_day(): void
    {
        Carbon::setTestNow('2026-05-20 09:00:00');

        MembershipPlan::query()->create([
            'name' => 'Bulanan',
            'duration_months' => 1,
            'price' => 90000,
        ]);

        $member = Member::query()->create([
            'full_name' => 'Member Pending',
            'joined_at' => '2026-04-26',
            'expires_at' => '2026-05-26',
        ]);

        $transaction = Transaction::query()->create([
            'invoice' => 'MP-TEST-001',
            'member_id' => $member->id,
            'customer_name' => $member->full_name,
            'type' => Transaction::TYPE_MEMBERSHIP,
            'description' => 'Perpanjangan Membership',
            'amount' => 90000,
            'paid_amount' => 90000,
            'payment_method' => 'qris',
            'payment_status' => 'pending',
            'transaction_at' => Carbon::now(),
            'notes' => 'Menunggu verifikasi',
        ]);

        $this->withSession([
            'auth' => [
                'role' => 'cashier',
                'login' => 'kasir',
            ],
        ])->post(route('cashier.verifications.confirm', $transaction->id))
            ->assertRedirect(route('cashier.receipts'));

        $member->refresh();
        $transaction->refresh();

        $this->assertSame('2026-06-26', $member->expires_at?->toDateString());
        $this->assertSame('verified', $transaction->payment_status);

        Carbon::setTestNow();
    }
}
