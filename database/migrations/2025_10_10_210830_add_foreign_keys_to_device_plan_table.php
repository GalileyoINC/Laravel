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
        Schema::table('device_plan', function (Blueprint $table) {
            $table->foreign(['id_device'], 'fk-device_plan-id_device')->references(['id'])->on('service')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_plan'], 'fk-device_plan-id_plan')->references(['id'])->on('service')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_plan', function (Blueprint $table) {
            $table->dropForeign('fk-device_plan-id_device');
            $table->dropForeign('fk-device_plan-id_plan');
        });
    }
};
