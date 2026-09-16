<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->nullOnDelete();
            $table->string('full_name');
            $table->string('email')->nullable()->unique();
            $table->string('phone', 30)->nullable();
            $table->string('checkin_code', 40)->nullable()->unique();
            $table->string('profile_photo_path')->nullable();
            $table->unsignedTinyInteger('profile_photo_change_count')->default(0);
            $table->date('joined_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->timestamp('last_membership_reminder_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
