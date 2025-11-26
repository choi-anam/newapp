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
        Schema::create('activity_log_settings', function (Blueprint $table) {
            $table->id();
            $table->string('model_class')->unique()->comment('Full class name of the model (e.g., App\Models\User)');
            $table->boolean('enabled')->default(true)->comment('Whether this model should be tracked');
            $table->json('tracked_attributes')->nullable()->comment('Specific attributes to log, null = all');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_log_settings');
    }
};
