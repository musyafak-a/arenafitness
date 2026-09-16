<?php

namespace Tests\Feature;

use App\Models\Checkin;
use App\Models\DailyGuest;
use App\Models\ExpenseRecord;
use App\Models\Member;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AdminReportsTest extends TestCase
{
    use RefreshDatabase;

    public function test_reports_page_shows_training_activity_membership_and_financial_data(): void
    {
        $member = Member::query()->create([
            'full_name' => 'Alya Fitri',
            'email' => 'alya@example.com',
            'phone' => '08123456789',
            'joined_at' => now()->subDays(5),
            'expires_at' => now()->addDays(25),
        ]);

        $guest = DailyGuest::query()->create([
            'full_name' => 'Tamu Harian',
            'phone' => '08123456788',
            'visit_type' => 'reguler',
            'visit_at' => now(),
        ]);

        Checkin::query()->create([
            'member_id' => $member->id,
            'checked_in_at' => now()->startOfMonth()->addDay()->setTime(8, 15),
            'verification_status' => 'verified',
            'notes' => 'Latihan kardio pagi',
        ]);

        Transaction::query()->create([
            'invoice' => 'INV-MBR-001',
            'member_id' => $member->id,
            'customer_name' => 'Alya Fitri',
            'type' => Transaction::TYPE_MEMBERSHIP,
            'description' => 'Paket Bulanan',
            'amount' => 50000,
            'paid_amount' => 50000,
            'change_amount' => 0,
            'payment_method' => 'qris',
            'payment_status' => 'verified',
            'transaction_at' => now()->startOfMonth()->addDays(1),
        ]);

        Transaction::query()->create([
            'invoice' => 'INV-GST-001',
            'daily_guest_id' => $guest->id,
            'customer_name' => 'Tamu Harian',
            'type' => Transaction::TYPE_DAILY_PASS,
            'description' => 'Daily Pass',
            'amount' => 30000,
            'paid_amount' => 30000,
            'change_amount' => 0,
            'payment_method' => 'cash',
            'payment_status' => 'verified',
            'transaction_at' => now()->startOfMonth()->addDays(2),
        ]);

        Transaction::query()->create([
            'invoice' => 'INV-OTH-001',
            'customer_name' => 'Pembeli Minuman',
            'type' => Transaction::TYPE_OTHER,
            'description' => 'Minuman',
            'amount' => 20000,
            'paid_amount' => 20000,
            'change_amount' => 0,
            'payment_method' => 'qris',
            'payment_status' => 'verified',
            'transaction_at' => now()->startOfMonth()->addDays(3),
        ]);

        ExpenseRecord::query()->create([
            'title' => 'Beli Suplemen Stok',
            'category' => 'inventaris',
            'amount' => 15000,
            'payment_method' => 'cash',
            'expense_date' => now()->startOfMonth()->addDays(4),
            'notes' => 'Restock etalase depan',
        ]);

        $this->withSession([
            'auth' => [
                'role' => 'admin',
                'login' => 'admin',
            ],
        ])->get(route('admin.reports'))
            ->assertOk()
            ->assertSee('Tambah pengeluaran')
            ->assertSee('Simpan Pengeluaran')
            ->assertSee('Laporan Member')
            ->assertSee('Laporan Keuangan')
            ->assertSee('Laporan Kehadiran')
            ->assertSee('Rp50.000')
            ->assertSee('Rp100.000')
            ->assertSee('Rp15.000')
            ->assertSee('Rp85.000');

        $this->withSession([
            'auth' => [
                'role' => 'admin',
                'login' => 'admin',
            ],
        ])->get(route('admin.reports.show', [
            'reportSlug' => 'laporan-kehadiran',
            'detail_month' => now()->format('Y-m'),
        ]))
            ->assertOk()
            ->assertSee('Alya Fitri')
            ->assertSee('Latihan kardio pagi');

        $this->withSession([
            'auth' => [
                'role' => 'admin',
                'login' => 'admin',
            ],
        ])->get(route('admin.reports.show', [
            'reportSlug' => 'laporan-keuangan',
            'detail_month' => now()->format('Y-m'),
        ]))
            ->assertOk()
            ->assertSee('Sumber Pendapatan')
            ->assertSee('Riwayat Pemasukan')
            ->assertSee('Riwayat Pengeluaran')
            ->assertSee('Beli Suplemen Stok');
    }

    public function test_stock_report_shows_sold_product_transaction_details_and_exports_them(): void
    {
        $category = \App\Models\Category::create([
            'name' => 'Vitamin',
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Whey Protein Vanilla',
            'brand' => 'FitFuel',
            'sku' => 'WF-001',
            'price' => 125000,
            'stock' => 8,
            'unit' => 'pcs',
            'is_active' => true,
        ]);

        $tx = Transaction::query()->create([
            'invoice' => 'PRD-DETAIL-001',
            'customer_name' => 'Bima Santoso',
            'type' => Transaction::TYPE_PRODUCT_SALE,
            'description' => $product->name,
            'amount' => 250000,
            'paid_amount' => 250000,
            'change_amount' => 0,
            'payment_method' => 'cash',
            'payment_status' => 'verified',
            'transaction_at' => now()->startOfMonth()->addDays(2)->setTime(13, 30),
        ]);

        TransactionItem::query()->create([
            'transaction_id' => $tx->id,
            'product_id' => $product->id,
            'item_name' => $product->name,
            'quantity' => 2,
            'unit_price' => 125000,
            'subtotal' => 250000,
        ]);

        $session = [
            'auth' => [
                'role' => 'admin',
                'login' => 'admin',
            ],
        ];

        $this->withSession($session)
            ->get(route('admin.reports.show', [
                'reportSlug' => 'laporan-stok-barang',
                'detail_month' => now()->format('Y-m'),
            ]))
            ->assertOk()
            ->assertSee('Whey Protein Vanilla');

        $this->withSession($session)
            ->get(route('admin.reports.show', [
                'reportSlug' => 'laporan-stok-barang',
                'detail_month' => now()->format('Y-m'),
                'export' => 1,
            ]))
            ->assertOk();
    }
}
