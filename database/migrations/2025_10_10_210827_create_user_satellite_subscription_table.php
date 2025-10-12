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
        Schema::create('user_satellite_subscription', function (Blueprint $table) {
            $table->bigInteger('id_user')->index('fk-user_satellite_subscription-id_user');
            $table->bigInteger('id_subscription')->index('fk-user_satellite_subscription-id_subscription');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_satellite_subscription');
    }
};
