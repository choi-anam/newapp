<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('password_reset_otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('email');
            $table->string('otp', 10)->comment('One-Time Password');
            $table->enum('channel', ['email', 'telegram', 'whatsapp'])->default('email');
            $table->timestamp('expires_at')->comment('OTP expiration time (15 minutes)');
            $table->boolean('is_used')->default(false);
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('email');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_otps');
    }
};
