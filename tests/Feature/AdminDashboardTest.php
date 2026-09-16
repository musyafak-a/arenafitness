<?php

namespace Tests\Feature;

use App\Models\DailyGuest;
use App\Models\ExpenseRecord;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_uses_database_values_for_income_and_expense_stats(): void
    {
        $member = Member::query()->create([
            'full_name' => 'Alya Fitri',
            'joined_at' => now()->subDays(3),
            'expires_at' => now()->addDays(27),
        ]);

        Transaction::query()->create([
            'invoice' => 'INV-DB-001',
            'member_id' => $member->id,
            'customer_name' => 'Alya Fitri',
            'type' => Transaction::TYPE_MEMBERSHIP,
            'description' => 'Paket Bulanan',
            'amount' => 50000,
            'paid_amount' => 50000,
            'change_amount' => 0,
            'payment_method' => 'qris',
            'payment_status' => 'verified',
            'transaction_at' => now(),
        ]);

        ExpenseRecord::query()->create([
            'title' => 'Beli Air Mineral',
            'category' => 'operasional',
            'amount' => 15000,
            'payment_method' => 'cash',
            'expense_date' => now(),
            'notes' => 'Stok front desk',
        ]);

        $this->withSession([
            'auth' => [
                'role' => 'admin',
                'login' => 'admin',
            ],
        ])->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Pemasukan Hari Ini')
            ->assertSee('Pengeluaran Hari Ini')
            ->assertSee('Laba Bersih Hari Ini')
            ->assertSee('Rp50.000')
            ->assertSee('Rp15.000')
            ->assertSee('Rp35.000');
    }

    public function test_recent_member_registrations_exclude_non_members(): void
    {
        Member::query()->create([
            'full_name' => 'Member Benar',
            'joined_at' => now(),
            'expires_at' => now()->addMonth(),
        ]);

        DailyGuest::query()->create([
            'full_name' => 'Guest Non Member Uji',
            'visit_type' => 'daily_pass',
            'visit_at' => now(),
        ]);

        $this->withSession([
            'auth' => [
                'role' => 'admin',
                'login' => 'admin',
            ],
        ])->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Member Benar')
            ->assertDontSee('Guest Non Member Uji');
    }
}
