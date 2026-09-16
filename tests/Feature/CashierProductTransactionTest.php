<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashierProductTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_can_sell_product_from_master_product_data(): void
    {
        $product = Product::query()->create([
            'name' => 'Vitamin C 1000',
            'brand' => 'Healthy Co',
            'sku' => 'VITC-001',
            'price' => 95000,
            'stock' => 10,
            'unit' => 'botol',
            'description' => 'Vitamin harian',
            'is_active' => true,
        ]);

        $session = [
            'auth' => [
                'role' => 'cashier',
                'login' => 'cashier',
            ],
        ];

        $this->withSession($session)
            ->post(route('cashier.transactions.store'), [
                'transaction_group' => 'product_sale',
                'customer_name' => 'Budi',
                'product_ids' => [$product->id],
                'quantities' => [
                    $product->id => 2,
                ],
                'payment_method' => 'cash',
                'paid_amount' => 200000,
                'notes' => 'Pembelian vitamin',
            ])
            ->assertRedirect(route('cashier.transactions.products'));

        $this->assertDatabaseHas('transactions', [
            'customer_name' => 'Budi',
            'type' => Transaction::TYPE_PRODUCT_SALE,
            'amount' => 190000,
            'paid_amount' => 200000,
            'change_amount' => 10000,
            'payment_status' => 'verified',
        ]);

        $this->assertDatabaseHas('transaction_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'subtotal' => 190000,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 8,
        ]);

        $this->withSession($session)
            ->get(route('cashier.transactions.products'))
            ->assertOk()
            ->assertSee('Cari')
            ->assertSee('Vitamin C 1000')
            ->assertSee('Budi')
            ->assertSee('Rp190.000');
    }
}
