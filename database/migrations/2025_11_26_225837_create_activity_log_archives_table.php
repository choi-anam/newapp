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
        Schema::create('activity_log_archives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id')->unique();
            $table->string('log_type')->default('manual'); // 'manual' or 'scheduled'
            $table->text('activity_data')->nullable(); // JSON backup of original activity
            $table->timestamp('archived_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_log_archives');
    }
};
