<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->foreignId('daily_guest_id')->nullable()->constrained('daily_guests')->nullOnDelete();
            $table->timestamp('checked_in_at');
            $table->string('checkin_method', 20)->default('admin'); // admin, qr_code, self_service
            $table->string('verification_status', 20)->default('verified'); // pending, verified, rejected
            $table->string('submitted_name')->nullable();
            $table->string('submitted_phone', 30)->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['member_id', 'checked_in_at']);
            $table->index('verification_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkins');
    }
};
