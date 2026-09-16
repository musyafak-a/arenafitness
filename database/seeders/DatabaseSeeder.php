<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── Users (Admin, Master Admin, Kasir) ───────────────────────────
        User::query()->updateOrCreate(
            ['login' => env('ADMIN_LOGIN', 'admin')],
            [
                'name' => 'Admin Arena Gym',
                'email' => 'admin@arena-gym.local',
                'role' => 'admin',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'admin123')),
            ]
        );

        User::query()->updateOrCreate(
            ['login' => env('MASTER_ADMIN_LOGIN', 'masteradmin')],
            [
                'name' => 'Master Admin Arena Gym',
                'email' => 'masteradmin@arena-gym.local',
                'role' => 'master_admin',
                'password' => Hash::make(env('MASTER_ADMIN_PASSWORD', 'master123')),
            ]
        );

        User::query()->updateOrCreate(
            ['role' => 'cashier'],
            [
                'login' => env('CASHIER_LOGIN', 'kasir'),
                'name' => 'Kasir Arena Gym',
                'email' => 'cashier@arena-gym.local',
                'password' => Hash::make(env('CASHIER_PASSWORD', 'kasir123')),
            ]
        );

        // ── Membership Plans ─────────────────────────────────────────────
        $plans = [
            ['name' => 'Bulanan',   'duration_months' => 1,  'price' => 90000,   'description' => 'Paket membership 1 bulan'],
            ['name' => '3 Bulan',   'duration_months' => 3,  'price' => 240000,  'description' => 'Paket membership 3 bulan'],
            ['name' => '6 Bulan',   'duration_months' => 6,  'price' => 450000,  'description' => 'Paket membership 6 bulan'],
            ['name' => 'Tahunan',   'duration_months' => 12, 'price' => 800000,  'description' => 'Paket membership 1 tahun'],
        ];

        foreach ($plans as $plan) {
            MembershipPlan::query()->updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }

        // ── Product Categories ───────────────────────────────────────────
        $categories = [
            ['name' => 'Suplemen',  'description' => 'Kategori produk suplemen fitness'],
            ['name' => 'Vitamin',   'description' => 'Kategori produk vitamin & kesehatan'],
            ['name' => 'Minuman',   'description' => 'Kategori minuman energi & protein'],
            ['name' => 'Aksesoris', 'description' => 'Kategori aksesoris gym'],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
