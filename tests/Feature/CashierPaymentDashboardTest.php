<?php

namespace Tests\Feature;

use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashierPaymentDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_dashboard_shows_all_payment_types_and_filters_them(): void
    {
        foreach ([
            ['INV-MEMBER-001', 'Alya Member', Transaction::TYPE_MEMBERSHIP, 'Membership 1 Bulan', 90000, 'cash', 'verified'],
            ['INV-DAILY-001', 'Bima Daily', Transaction::TYPE_DAILY_PASS, 'Daily Pass', 30000, 'cash', 'verified'],
            ['INV-PRODUCT-001', 'Citra Produk', Transaction::TYPE_PRODUCT_SALE, 'Vitamin C', 95000, 'qris', 'pending'],
            ['INV-OTHER-001', 'Dedi Lainnya', Transaction::TYPE_OTHER, 'Sewa Loker', 15000, 'cash', 'verified'],
        ] as [$invoice, $customer, $type, $desc, $amount, $method, $status]) {
            Transaction::query()->create([
                'invoice' => $invoice,
                'customer_name' => $customer,
                'type' => $type,
                'description' => $desc,
                'amount' => $amount,
                'paid_amount' => $amount,
                'change_amount' => 0,
                'payment_method' => $method,
                'payment_status' => $status,
                'transaction_at' => now(),
            ]);
        }

        $session = [
            'auth' => [
                'role' => 'cashier',
                'login' => 'kasir',
            ],
        ];

        $this->withSession($session)
            ->get(route('cashier.transactions'))
            ->assertOk()
            ->assertSee('Alya Member')
            ->assertSee('Bima Daily')
            ->assertSee('Citra Produk')
            ->assertSee('Dedi Lainnya');

        $this->withSession($session)
            ->get(route('cashier.transactions', ['type' => Transaction::TYPE_PRODUCT_SALE]))
            ->assertOk()
            ->assertSee('Citra Produk')
            ->assertDontSee('Alya Member')
            ->assertDontSee('Bima Daily')
            ->assertDontSee('Dedi Lainnya');
    }
}
