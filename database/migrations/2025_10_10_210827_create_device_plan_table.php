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
        Schema::create('device_plan', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_device')->nullable()->index('fk-device_plan-id_device');
            $table->bigInteger('id_plan')->nullable()->index('fk-device_plan-id_plan');
            $table->boolean('is_default')->default(false);
            $table->decimal('price', 10)->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_plan');
    }
};
