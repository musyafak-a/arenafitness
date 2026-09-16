<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice')->unique();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->foreignId('daily_guest_id')->nullable()->constrained('daily_guests')->nullOnDelete();
            $table->foreignId('cashier_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type', 30);                  // membership_payment, daily_pass, product_sale, other
            $table->string('customer_name')->nullable();
            $table->string('description')->nullable();
            $table->unsignedInteger('amount');
            $table->unsignedInteger('paid_amount')->nullable();
            $table->unsignedInteger('change_amount')->nullable();
            $table->string('payment_method', 30);         // cash, qris, transfer
            $table->string('payment_status', 20)->default('verified'); // pending, verified, cancelled
            $table->timestamp('transaction_at');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['type', 'transaction_at']);
            $table->index('payment_status');
            $table->index('member_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
