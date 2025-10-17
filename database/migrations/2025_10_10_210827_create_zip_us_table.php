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
        Schema::create('zip_us', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('zip', 8);
            $table->text('geopoint')->nullable(); // Changed from geometry to text for SQLite compatibility
            $table->string('city', 32)->nullable();
            $table->string('state', 2)->nullable();
            $table->string('timezone', 3)->nullable();
            $table->string('daylight_savings_time_flag', 1)->nullable();

            // Removed spatial index as it's not supported in SQLite
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zip_us');
    }
};
