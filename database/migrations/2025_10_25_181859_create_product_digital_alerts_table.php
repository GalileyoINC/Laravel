<?php

declare(strict_types=1);

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
        Schema::create('product_digital_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('status');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('alert_data')->nullable();
            
            // Map fields
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('address')->nullable();
            $table->enum('severity', ['critical', 'high', 'medium', 'low'])->default('medium');
            $table->string('category')->nullable();
            $table->decimal('affected_radius', 8, 2)->nullable(); // km
            $table->string('source')->nullable();
            $table->timestamp('expires_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_digital_alerts');
    }
};