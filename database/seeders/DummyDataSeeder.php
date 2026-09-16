<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\Checkin;
use App\Models\DailyGuest;
use App\Models\ExpenseRecord;
use App\Models\Member;
use App\Models\MemberFeedback;
use App\Models\MembershipPlan;
use App\Models\MembershipRenewalRequest;
use App\Models\MembershipSubscription;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the dummy data seeder.
     */
    public function run(): void
    {
        $this->command->info('🏋️ Seeding dummy data for Arena Gym...');

        // Ensure base data exists (plans, categories, admin users)
        $this->call(DatabaseSeeder::class);

        $plans    = MembershipPlan::all()->keyBy('name');
        $categories = Category::all()->keyBy('name');
        $cashier  = User::where('role', 'cashier')->first();
        $admin    = User::where('role', 'admin')->first();

        // ── 1. Products ──────────────────────────────────────────────────
        $this->command->info('  📦 Creating products...');
        $products = $this->seedProducts($categories);

        // ── 2. Members with User accounts ────────────────────────────────
        $this->command->info('  👤 Creating members...');
        $members = $this->seedMembers($plans);

        // ── 3. Daily Guests ──────────────────────────────────────────────
        $this->command->info('  🚶 Creating daily guests...');
        $dailyGuests = $this->seedDailyGuests();

        // ── 4. Subscriptions ─────────────────────────────────────────────
        $this->command->info('  📋 Creating membership subscriptions...');
        $this->seedSubscriptions($members, $plans);

        // ── 5. Check-ins ─────────────────────────────────────────────────
        $this->command->info('  ✅ Creating check-ins...');
        $this->seedCheckins($members, $dailyGuests, $cashier);

        // ── 6. Transactions ──────────────────────────────────────────────
        $this->command->info('  💰 Creating transactions...');
        $this->seedTransactions($members, $dailyGuests, $products, $plans, $cashier);

        // ── 7. Expense Records ───────────────────────────────────────────
        $this->command->info('  📉 Creating expense records...');
        $this->seedExpenseRecords();

        // ── 8. Announcements ─────────────────────────────────────────────
        $this->command->info('  📢 Creating announcements...');
        $this->seedAnnouncements();

        // ── 9. Member Feedbacks ──────────────────────────────────────────
        $this->command->info('  💬 Creating member feedbacks...');
        $this->seedFeedbacks($members);

        // ── 10. Renewal Requests ─────────────────────────────────────────
        $this->command->info('  🔄 Creating renewal requests...');
        $this->seedRenewalRequests($members, $plans, $admin);

        $this->command->info('✅ Dummy data seeding complete!');
    }

    // =====================================================================
    //  PRODUCTS
    // =====================================================================
    private function seedProducts($categories): array
    {
        $items = [
            // Suplemen
            ['category' => 'Suplemen', 'name' => 'Whey Protein Gold Standard', 'brand' => 'Optimum Nutrition', 'sku' => 'SUP-001', 'price' => 850000, 'stock' => 12, 'unit' => 'pcs', 'description' => 'Whey protein isolate 5 lbs'],
            ['category' => 'Suplemen', 'name' => 'Mass Gainer Serious Mass', 'brand' => 'Optimum Nutrition', 'sku' => 'SUP-002', 'price' => 650000, 'stock' => 8, 'unit' => 'pcs', 'description' => 'Mass gainer 6 lbs untuk bulking'],
            ['category' => 'Suplemen', 'name' => 'BCAA Powder', 'brand' => 'MuscleTech', 'sku' => 'SUP-003', 'price' => 320000, 'stock' => 15, 'unit' => 'pcs', 'description' => 'BCAA amino acid recovery'],
            ['category' => 'Suplemen', 'name' => 'Creatine Monohydrate', 'brand' => 'MuscleTech', 'sku' => 'SUP-004', 'price' => 275000, 'stock' => 20, 'unit' => 'pcs', 'description' => 'Creatine monohydrate 300g'],

            // Vitamin
            ['category' => 'Vitamin', 'name' => 'Multivitamin Daily', 'brand' => 'Nature Made', 'sku' => 'VIT-001', 'price' => 185000, 'stock' => 25, 'unit' => 'botol', 'description' => 'Multivitamin harian 60 tablet'],
            ['category' => 'Vitamin', 'name' => 'Fish Oil Omega 3', 'brand' => 'Blackmores', 'sku' => 'VIT-002', 'price' => 210000, 'stock' => 18, 'unit' => 'botol', 'description' => 'Minyak ikan omega 3 90 kapsul'],

            // Minuman
            ['category' => 'Minuman', 'name' => 'Protein Shake RTD', 'brand' => 'Muscle First', 'sku' => 'MNM-001', 'price' => 35000, 'stock' => 48, 'unit' => 'botol', 'description' => 'Ready-to-drink protein shake 250ml'],
            ['category' => 'Minuman', 'name' => 'Energy Drink Pre-Workout', 'brand' => 'C4', 'sku' => 'MNM-002', 'price' => 45000, 'stock' => 36, 'unit' => 'botol', 'description' => 'Pre-workout energy drink'],

            // Aksesoris
            ['category' => 'Aksesoris', 'name' => 'Gym Gloves Premium', 'brand' => 'RDX', 'sku' => 'AKS-001', 'price' => 150000, 'stock' => 10, 'unit' => 'pcs', 'description' => 'Sarung tangan gym kulit sintetis'],
            ['category' => 'Aksesoris', 'name' => 'Wrist Wrap Support', 'brand' => 'Harbinger', 'sku' => 'AKS-002', 'price' => 120000, 'stock' => 14, 'unit' => 'pcs', 'description' => 'Wrist wrap untuk angkat berat'],
        ];

        $created = [];
        foreach ($items as $item) {
            $categoryId = $categories[$item['category']]->id ?? null;
            unset($item['category']);
            $created[] = Product::updateOrCreate(
                ['sku' => $item['sku']],
                array_merge($item, ['category_id' => $categoryId, 'is_active' => true])
            );
        }

        return $created;
    }

    // =====================================================================
    //  MEMBERS (with User accounts)
    // =====================================================================
    private function seedMembers($plans): array
    {
        $memberData = [
            // Active members (membership masih berlaku)
            ['full_name' => 'Ahmad Rizki Pratama',  'email' => 'ahmad.rizki@gmail.com',    'phone' => '081234567890', 'joined_months_ago' => 6,  'status' => 'active',  'plan' => 'Bulanan'],
            ['full_name' => 'Budi Santoso',         'email' => 'budi.santoso@gmail.com',   'phone' => '081345678901', 'joined_months_ago' => 4,  'status' => 'active',  'plan' => '3 Bulan'],
            ['full_name' => 'Citra Dewi Lestari',   'email' => 'citra.dewi@gmail.com',     'phone' => '081456789012', 'joined_months_ago' => 8,  'status' => 'active',  'plan' => '6 Bulan'],
            ['full_name' => 'Deni Firmansyah',      'email' => 'deni.firman@gmail.com',    'phone' => '081567890123', 'joined_months_ago' => 2,  'status' => 'active',  'plan' => 'Bulanan'],
            ['full_name' => 'Eka Putri Rahayu',     'email' => 'eka.putri@gmail.com',      'phone' => '081678901234', 'joined_months_ago' => 10, 'status' => 'active',  'plan' => 'Tahunan'],
            ['full_name' => 'Fajar Nugroho',        'email' => 'fajar.nugroho@gmail.com',  'phone' => '081789012345', 'joined_months_ago' => 1,  'status' => 'active',  'plan' => 'Bulanan'],
            ['full_name' => 'Gita Ayu Permata',     'email' => 'gita.ayu@gmail.com',       'phone' => '081890123456', 'joined_months_ago' => 3,  'status' => 'active',  'plan' => '3 Bulan'],
            ['full_name' => 'Hendra Wijaya',        'email' => 'hendra.w@gmail.com',       'phone' => '081901234567', 'joined_months_ago' => 5,  'status' => 'active',  'plan' => '6 Bulan'],

            // Expired members (membership sudah habis)
            ['full_name' => 'Irfan Maulana',        'email' => 'irfan.maul@gmail.com',     'phone' => '082012345678', 'joined_months_ago' => 5,  'status' => 'expired', 'plan' => 'Bulanan'],
            ['full_name' => 'Joko Widodo Saputra',  'email' => 'joko.saputra@gmail.com',   'phone' => '082123456789', 'joined_months_ago' => 7,  'status' => 'expired', 'plan' => '3 Bulan'],
            ['full_name' => 'Kartika Sari',         'email' => 'kartika.sari@gmail.com',   'phone' => '082234567890', 'joined_months_ago' => 9,  'status' => 'expired', 'plan' => 'Bulanan'],
            ['full_name' => 'Lukman Hakim',         'email' => 'lukman.hakim@gmail.com',   'phone' => '082345678901', 'joined_months_ago' => 4,  'status' => 'expired', 'plan' => 'Bulanan'],

            // New members (baru bergabung, belum banyak check-in)
            ['full_name' => 'Maya Anggraini',       'email' => 'maya.anggraini@gmail.com', 'phone' => '082456789012', 'joined_months_ago' => 0,  'status' => 'active',  'plan' => 'Bulanan'],
            ['full_name' => 'Nanda Prasetyo',       'email' => 'nanda.pras@gmail.com',     'phone' => '082567890123', 'joined_months_ago' => 0,  'status' => 'active',  'plan' => '3 Bulan'],
            ['full_name' => 'Olivia Putri Salsabila','email' => 'olivia.ps@gmail.com',     'phone' => '082678901234', 'joined_months_ago' => 0,  'status' => 'active',  'plan' => 'Bulanan'],
        ];

        $members = [];

        foreach ($memberData as $i => $data) {
            $joinedAt = now()->subMonths($data['joined_months_ago'])->subDays(rand(0, 15));
            $plan = $plans[$data['plan']];

            if ($data['status'] === 'active') {
                $expiresAt = now()->addDays(rand(5, 25));
            } else {
                $expiresAt = now()->subDays(rand(5, 45));
            }

            // Create user account for member
            $login = Str::slug($data['full_name'], '.');
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['full_name'],
                    'login'    => $login,
                    'role'     => 'member',
                    'password' => Hash::make('member123'),
                ]
            );

            $member = Member::updateOrCreate(
                ['email' => $data['email']],
                [
                    'user_id'      => $user->id,
                    'full_name'    => $data['full_name'],
                    'phone'        => $data['phone'],
                    'checkin_code' => 'MBR-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                    'joined_at'    => $joinedAt->toDateString(),
                    'expires_at'   => $expiresAt->toDateString(),
                    'notes'        => null,
                ]
            );

            $members[] = ['model' => $member, 'plan' => $plan, 'status' => $data['status']];
        }

        return $members;
    }

    // =====================================================================
    //  DAILY GUESTS
    // =====================================================================
    private function seedDailyGuests(): array
    {
        $guests = [
            ['full_name' => 'Rudi Setiawan',     'phone' => '083012345678', 'visit_type' => 'reguler', 'days_ago' => 1],
            ['full_name' => 'Sinta Maharani',     'phone' => '083123456789', 'visit_type' => 'reguler', 'days_ago' => 2],
            ['full_name' => 'Toni Kusuma',        'phone' => '083234567890', 'visit_type' => 'reguler', 'days_ago' => 3],
            ['full_name' => 'Umi Kalsum',         'phone' => '083345678901', 'visit_type' => 'reguler', 'days_ago' => 5],
            ['full_name' => 'Vino Pratama',       'phone' => '083456789012', 'visit_type' => 'reguler', 'days_ago' => 7],
            ['full_name' => 'Wahyu Ramadhan',     'phone' => '083567890123', 'visit_type' => 'event',   'days_ago' => 10],
            ['full_name' => 'Xena Putri',         'phone' => '083678901234', 'visit_type' => 'reguler', 'days_ago' => 12],
            ['full_name' => 'Yoga Aditya',        'phone' => '083789012345', 'visit_type' => 'reguler', 'days_ago' => 0],
            ['full_name' => 'Zahra Aulia',        'phone' => '083890123456', 'visit_type' => 'reguler', 'days_ago' => 0],
            ['full_name' => 'Andika Putra',       'phone' => '083901234567', 'visit_type' => 'event',   'days_ago' => 15],
        ];

        $created = [];
        foreach ($guests as $g) {
            $visitAt = now()->subDays($g['days_ago'])->setTime(rand(7, 19), rand(0, 59));
            $created[] = DailyGuest::updateOrCreate(
                ['full_name' => $g['full_name'], 'phone' => $g['phone']],
                [
                    'visit_type' => $g['visit_type'],
                    'visit_at'   => $visitAt,
                ]
            );
        }

        return $created;
    }

    // =====================================================================
    //  SUBSCRIPTIONS
    // =====================================================================
    private function seedSubscriptions(array $members, $plans): void
    {
        foreach ($members as $m) {
            $member = $m['model'];
            $plan   = $m['plan'];

            // Current or most recent subscription
            if ($m['status'] === 'active') {
                $startDate = Carbon::parse($member->expires_at)->subMonths($plan->duration_months);
                $status = 'active';
            } else {
                $startDate = Carbon::parse($member->expires_at)->subMonths($plan->duration_months);
                $status = 'expired';
            }

            MembershipSubscription::updateOrCreate(
                ['member_id' => $member->id, 'start_date' => $startDate->toDateString()],
                [
                    'membership_plan_id' => $plan->id,
                    'end_date'           => Carbon::parse($member->expires_at)->toDateString(),
                    'amount_paid'        => $plan->price,
                    'payment_method'     => collect(['cash', 'qris', 'transfer'])->random(),
                    'status'             => $status,
                ]
            );

            // Some members have previous subscriptions (history)
            if ($m['model']->joined_at && $m['model']->joined_at->diffInMonths(now()) > 3) {
                $prevEnd = $startDate->copy()->subDays(1);
                $prevStart = $prevEnd->copy()->subMonths($plan->duration_months);

                MembershipSubscription::updateOrCreate(
                    ['member_id' => $member->id, 'start_date' => $prevStart->toDateString()],
                    [
                        'membership_plan_id' => $plan->id,
                        'end_date'           => $prevEnd->toDateString(),
                        'amount_paid'        => $plan->price,
                        'payment_method'     => collect(['cash', 'qris'])->random(),
                        'status'             => 'expired',
                    ]
                );
            }
        }
    }

    // =====================================================================
    //  CHECK-INS
    // =====================================================================
    private function seedCheckins(array $members, array $dailyGuests, $cashier): void
    {
        $methods = ['admin', 'qr_code', 'self_service'];

        // Member check-ins (spread across last 30 days)
        foreach ($members as $m) {
            $member = $m['model'];
            if ($m['status'] === 'expired') {
                // Expired members: only old check-ins
                $checkinCount = rand(3, 8);
                for ($i = 0; $i < $checkinCount; $i++) {
                    $daysAgo = rand(30, 90);
                    $checkedInAt = now()->subDays($daysAgo)->setTime(rand(6, 21), rand(0, 59));
                    $method = $methods[array_rand($methods)];

                    Checkin::updateOrCreate(
                        ['member_id' => $member->id, 'checked_in_at' => $checkedInAt],
                        [
                            'checkin_method'      => $method,
                            'verification_status' => 'verified',
                            'verified_at'         => $checkedInAt->copy()->addMinutes(rand(0, 5)),
                            'verified_by'         => $cashier?->id,
                        ]
                    );
                }
            } else {
                // Active members: recent check-ins
                $frequency = rand(3, 6); // check-ins per week roughly
                $checkinCount = rand(5, 20);
                for ($i = 0; $i < $checkinCount; $i++) {
                    $daysAgo = rand(0, 30);
                    $checkedInAt = now()->subDays($daysAgo)->setTime(rand(6, 21), rand(0, 59));
                    $method = $methods[array_rand($methods)];

                    $isVerified = $method !== 'self_service' || rand(0, 4) > 0;

                    Checkin::updateOrCreate(
                        ['member_id' => $member->id, 'checked_in_at' => $checkedInAt],
                        [
                            'checkin_method'      => $method,
                            'verification_status' => $isVerified ? 'verified' : 'pending',
                            'verified_at'         => $isVerified ? $checkedInAt->copy()->addMinutes(rand(0, 10)) : null,
                            'verified_by'         => $isVerified ? $cashier?->id : null,
                        ]
                    );
                }
            }
        }

        // Daily guest check-ins
        foreach ($dailyGuests as $guest) {
            Checkin::updateOrCreate(
                ['daily_guest_id' => $guest->id, 'checked_in_at' => $guest->visit_at],
                [
                    'checkin_method'      => 'admin',
                    'verification_status' => 'verified',
                    'verified_at'         => Carbon::parse($guest->visit_at)->addMinutes(2),
                    'verified_by'         => $cashier?->id,
                ]
            );
        }
    }

    // =====================================================================
    //  TRANSACTIONS
    // =====================================================================
    private function seedTransactions(array $members, array $dailyGuests, array $products, $plans, $cashier): void
    {
        $invoiceCounter = 1;

        // ── Membership payment transactions ──────────────────────────────
        foreach ($members as $m) {
            $member = $m['model'];
            $plan   = $m['plan'];
            $amount = $plan->price;

            $txAt = Carbon::parse($member->joined_at ?? now()->subMonths(1));

            $paidAmount = $amount; // Bayar pas
            if (rand(0, 3) === 0) {
                // Kadang bayar lebih (kembalian)
                $paidAmount = (int) ceil($amount / 50000) * 50000;
            }

            Transaction::updateOrCreate(
                ['invoice' => 'INV-' . now()->format('Ymd') . '-' . str_pad($invoiceCounter, 4, '0', STR_PAD_LEFT)],
                [
                    'member_id'      => $member->id,
                    'cashier_user_id'=> $cashier?->id,
                    'type'           => Transaction::TYPE_MEMBERSHIP,
                    'customer_name'  => $member->full_name,
                    'description'    => 'Pembayaran membership ' . $plan->name,
                    'amount'         => $amount,
                    'paid_amount'    => $paidAmount,
                    'change_amount'  => max(0, $paidAmount - $amount),
                    'payment_method' => collect(['cash', 'qris', 'transfer'])->random(),
                    'payment_status' => 'verified',
                    'transaction_at' => $txAt,
                ]
            );
            $invoiceCounter++;
        }

        // ── Daily pass transactions ──────────────────────────────────────
        $dailyPassPrice = 25000;
        foreach ($dailyGuests as $guest) {
            Transaction::updateOrCreate(
                ['invoice' => 'INV-' . now()->format('Ymd') . '-' . str_pad($invoiceCounter, 4, '0', STR_PAD_LEFT)],
                [
                    'daily_guest_id' => $guest->id,
                    'cashier_user_id'=> $cashier?->id,
                    'type'           => Transaction::TYPE_DAILY_PASS,
                    'customer_name'  => $guest->full_name,
                    'description'    => 'Daily pass - ' . $guest->visit_type,
                    'amount'         => $dailyPassPrice,
                    'paid_amount'    => $dailyPassPrice,
                    'change_amount'  => 0,
                    'payment_method' => collect(['cash', 'qris'])->random(),
                    'payment_status' => 'verified',
                    'transaction_at' => $guest->visit_at,
                ]
            );
            $invoiceCounter++;
        }

        // ── Product sale transactions ────────────────────────────────────
        $productSales = [
            ['days_ago' => 0,  'product_indices' => [6, 7],       'customer' => 'Ahmad Rizki Pratama',  'member_index' => 0],
            ['days_ago' => 1,  'product_indices' => [0],          'customer' => 'Budi Santoso',         'member_index' => 1],
            ['days_ago' => 2,  'product_indices' => [2, 4],       'customer' => 'Citra Dewi Lestari',   'member_index' => 2],
            ['days_ago' => 3,  'product_indices' => [6],          'customer' => 'Walk-in Customer',     'member_index' => null],
            ['days_ago' => 5,  'product_indices' => [1],          'customer' => 'Fajar Nugroho',        'member_index' => 5],
            ['days_ago' => 7,  'product_indices' => [7, 8],       'customer' => 'Gita Ayu Permata',     'member_index' => 6],
            ['days_ago' => 10, 'product_indices' => [3, 5],       'customer' => 'Hendra Wijaya',        'member_index' => 7],
            ['days_ago' => 12, 'product_indices' => [6, 6, 7],    'customer' => 'Walk-in Customer 2',   'member_index' => null],
            ['days_ago' => 15, 'product_indices' => [0, 2],       'customer' => 'Eka Putri Rahayu',     'member_index' => 4],
            ['days_ago' => 20, 'product_indices' => [9],          'customer' => 'Deni Firmansyah',      'member_index' => 3],
        ];

        foreach ($productSales as $sale) {
            $totalAmount = 0;
            $itemsData = [];

            // Calculate quantities per product
            $productQty = [];
            foreach ($sale['product_indices'] as $pi) {
                if (isset($products[$pi])) {
                    $productId = $products[$pi]->id;
                    if (!isset($productQty[$productId])) {
                        $productQty[$productId] = ['product' => $products[$pi], 'qty' => 0];
                    }
                    $productQty[$productId]['qty']++;
                }
            }

            foreach ($productQty as $pData) {
                $product = $pData['product'];
                $qty     = $pData['qty'];
                $subtotal = $product->price * $qty;
                $totalAmount += $subtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'item_name'  => $product->name,
                    'quantity'   => $qty,
                    'unit_price' => $product->price,
                    'subtotal'   => $subtotal,
                ];
            }

            $txAt = now()->subDays($sale['days_ago'])->setTime(rand(9, 20), rand(0, 59));
            $memberId = $sale['member_index'] !== null ? $members[$sale['member_index']]['model']->id : null;

            $paidAmount = (int) ceil($totalAmount / 10000) * 10000;

            $tx = Transaction::updateOrCreate(
                ['invoice' => 'INV-' . now()->format('Ymd') . '-' . str_pad($invoiceCounter, 4, '0', STR_PAD_LEFT)],
                [
                    'member_id'      => $memberId,
                    'cashier_user_id'=> $cashier?->id,
                    'type'           => Transaction::TYPE_PRODUCT_SALE,
                    'customer_name'  => $sale['customer'],
                    'description'    => 'Pembelian produk',
                    'amount'         => $totalAmount,
                    'paid_amount'    => $paidAmount,
                    'change_amount'  => max(0, $paidAmount - $totalAmount),
                    'payment_method' => collect(['cash', 'qris', 'transfer'])->random(),
                    'payment_status' => 'verified',
                    'transaction_at' => $txAt,
                ]
            );

            // Create transaction items
            foreach ($itemsData as $item) {
                TransactionItem::updateOrCreate(
                    ['transaction_id' => $tx->id, 'product_id' => $item['product_id']],
                    $item
                );
            }

            $invoiceCounter++;
        }
    }

    // =====================================================================
    //  EXPENSE RECORDS
    // =====================================================================
    private function seedExpenseRecords(): void
    {
        $expenses = [
            ['title' => 'Bayar listrik bulan September',    'category' => 'Utilitas',    'amount' => 1500000, 'days_ago' => 2],
            ['title' => 'Bayar air PDAM',                   'category' => 'Utilitas',    'amount' => 350000,  'days_ago' => 2],
            ['title' => 'Beli pembersih lantai & alat pel', 'category' => 'Kebersihan',  'amount' => 175000,  'days_ago' => 5],
            ['title' => 'Service AC ruang gym',             'category' => 'Perawatan',   'amount' => 500000,  'days_ago' => 8],
            ['title' => 'Gaji karyawan kasir',              'category' => 'Gaji',        'amount' => 3500000, 'days_ago' => 15],
            ['title' => 'Gaji karyawan cleaning',           'category' => 'Gaji',        'amount' => 2500000, 'days_ago' => 15],
            ['title' => 'Beli tali resistance band',        'category' => 'Peralatan',   'amount' => 450000,  'days_ago' => 20],
            ['title' => 'Internet WiFi bulanan',            'category' => 'Utilitas',    'amount' => 500000,  'days_ago' => 3],
            ['title' => 'Beli handuk gym (20 pcs)',         'category' => 'Perlengkapan','amount' => 600000,  'days_ago' => 25],
            ['title' => 'Bayar pajak PBB',                  'category' => 'Pajak',       'amount' => 1200000, 'days_ago' => 30],
            ['title' => 'Beli galon air mineral',           'category' => 'Operasional', 'amount' => 120000,  'days_ago' => 1],
            ['title' => 'Perbaikan kabel treadmill',        'category' => 'Perawatan',   'amount' => 350000,  'days_ago' => 12],
        ];

        foreach ($expenses as $exp) {
            ExpenseRecord::updateOrCreate(
                ['title' => $exp['title'], 'expense_date' => now()->subDays($exp['days_ago'])->toDateString()],
                [
                    'category'       => $exp['category'],
                    'amount'         => $exp['amount'],
                    'payment_method' => $exp['days_ago'] > 10 ? 'transfer' : 'cash',
                    'notes'          => null,
                ]
            );
        }
    }

    // =====================================================================
    //  ANNOUNCEMENTS
    // =====================================================================
    private function seedAnnouncements(): void
    {
        $announcements = [
            [
                'title'  => 'Promo Membership Akhir Tahun!',
                'body'   => "Dapatkan diskon 20% untuk semua paket membership!\n\nBerlaku mulai 1 Desember - 31 Desember 2026.\nDaftarkan diri Anda atau ajak teman Anda untuk bergabung di Arena Gym.\n\nSyarat & ketentuan berlaku.",
                'status' => 'published',
                'publish_at' => now()->subDays(5),
            ],
            [
                'title'  => 'Jadwal Operasional Hari Raya',
                'body'   => "Informasi kepada seluruh member Arena Gym,\n\nPada tanggal 25-26 Desember 2026, gym akan beroperasi dengan jam terbatas:\n- Buka: 08:00 - 14:00 WIB\n\nTerima kasih atas pengertiannya.",
                'status' => 'published',
                'publish_at' => now()->subDays(2),
            ],
            [
                'title'  => 'Kelas Yoga Gratis Setiap Sabtu',
                'body'   => "Arena Gym mengadakan kelas yoga GRATIS untuk semua member setiap hari Sabtu pukul 07:00 - 08:30 WIB.\n\nDaftarkan diri Anda di meja resepsionis.\nKuota terbatas: 20 orang per sesi.",
                'status' => 'published',
                'publish_at' => now()->subDays(10),
            ],
            [
                'title'  => 'Maintenance Alat Cardio',
                'body'   => "Pemberitahuan: Beberapa alat cardio (treadmill & elliptical) sedang dalam perawatan rutin pada tanggal 18 September 2026.\n\nMohon maaf atas ketidaknyamanannya.",
                'status' => 'draft',
                'publish_at' => null,
            ],
            [
                'title'  => 'Program Referral Member',
                'body'   => "Ajak teman Anda bergabung dan dapatkan perpanjangan membership 1 minggu GRATIS!\n\nSetiap teman yang berhasil mendaftar melalui referral Anda, Anda mendapatkan bonus 7 hari.\n\nMaximum 4 referral per bulan.",
                'status' => 'archived',
                'publish_at' => now()->subMonths(2),
                'archived_at' => now()->subDays(15),
            ],
        ];

        foreach ($announcements as $a) {
            Announcement::updateOrCreate(
                ['title' => $a['title']],
                [
                    'body'        => $a['body'],
                    'status'      => $a['status'],
                    'publish_at'  => $a['publish_at'] ?? null,
                    'archived_at' => $a['archived_at'] ?? null,
                ]
            );
        }
    }

    // =====================================================================
    //  MEMBER FEEDBACKS
    // =====================================================================
    private function seedFeedbacks(array $members): void
    {
        $feedbacks = [
            ['member_index' => 0, 'subject' => 'AC di ruang angkat beban kurang dingin', 'message' => 'Mohon AC di area angkat beban bisa diperbaiki, beberapa hari terakhir terasa panas terutama sore hari. Terima kasih.', 'read' => true],
            ['member_index' => 2, 'subject' => 'Saran penambahan alat lat pulldown', 'message' => 'Untuk area back exercise, sepertinya perlu ditambah 1 unit lat pulldown lagi karena sering antri terutama jam 17-19.', 'read' => true],
            ['member_index' => 4, 'subject' => 'Terima kasih pelayanannya!', 'message' => 'Saya sudah member hampir 1 tahun dan sangat puas dengan pelayanan Arena Gym. Staff ramah, tempat bersih. Tetap pertahankan kualitasnya!', 'read' => true],
            ['member_index' => 5, 'subject' => 'Request musik lebih bervariasi', 'message' => 'Boleh tidak playlist musiknya divariasikan? Kadang-kadang genre hip hop/EDM buat motivasi angkat beban.', 'read' => false],
            ['member_index' => 7, 'subject' => 'Loker kadang macet', 'message' => 'Loker nomor 12 dan 15 kuncinya agak susah dibuka, mungkin perlu diganti. Terima kasih atas perhatiannya.', 'read' => false],
        ];

        foreach ($feedbacks as $fb) {
            $member = $members[$fb['member_index']]['model'];

            MemberFeedback::updateOrCreate(
                ['member_id' => $member->id, 'subject' => $fb['subject']],
                [
                    'user_id' => $member->user_id,
                    'name'    => $member->full_name,
                    'email'   => $member->email,
                    'message' => $fb['message'],
                    'read_at' => $fb['read'] ? now()->subDays(rand(1, 5)) : null,
                ]
            );
        }
    }

    // =====================================================================
    //  RENEWAL REQUESTS
    // =====================================================================
    private function seedRenewalRequests(array $members, $plans, $admin): void
    {
        // Some expired members have pending renewal requests
        $renewals = [
            ['member_index' => 8,  'plan' => 'Bulanan',  'status' => 'pending',  'payment_method' => 'qris'],
            ['member_index' => 9,  'plan' => '3 Bulan',  'status' => 'pending',  'payment_method' => 'transfer'],
            ['member_index' => 10, 'plan' => 'Bulanan',  'status' => 'rejected', 'payment_method' => 'qris'],
            ['member_index' => 11, 'plan' => '6 Bulan',  'status' => 'approved', 'payment_method' => 'transfer'],
        ];

        foreach ($renewals as $r) {
            $member = $members[$r['member_index']]['model'];
            $plan   = $plans[$r['plan']];

            MembershipRenewalRequest::updateOrCreate(
                ['member_id' => $member->id, 'status' => $r['status']],
                [
                    'user_id'            => $member->user_id,
                    'membership_plan_id' => $plan->id,
                    'amount'             => $plan->price,
                    'payment_method'     => $r['payment_method'],
                    'payment_proof_path' => null,
                    'requested_at'       => now()->subDays(rand(1, 7)),
                    'reviewed_at'        => $r['status'] !== 'pending' ? now()->subDays(rand(0, 2)) : null,
                    'reviewed_by'        => $r['status'] !== 'pending' ? $admin?->id : null,
                ]
            );
        }
    }
}
