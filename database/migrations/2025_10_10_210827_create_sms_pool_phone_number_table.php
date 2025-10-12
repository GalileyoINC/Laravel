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
        Schema::create('sms_pool_phone_number', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_sms_pool')->nullable()->index('idx_sms_pool_phone_number_sms');
            $table->bigInteger('id_user')->nullable()->index('idx_sms_pool_phone_number_user');
            $table->bigInteger('id_phone_number')->nullable()->index('fk-sms_pool_phone_number-id_phone_number');
            $table->string('number')->nullable();
            $table->boolean('is_satellite')->nullable()->default(false);
            $table->tinyInteger('status')->default(1)->index('idx_sms_pool_phone_number_status');
            $table->string('sid', 64)->nullable();
            $table->text('error')->nullable();
            $table->bigInteger('id_provider')->nullable()->index('fk-sms_pool_phone_number-id_provider');
            $table->tinyInteger('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_pool_phone_number');
    }
};
