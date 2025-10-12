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
        Schema::create('user_plan', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_user')->nullable()->index('fk-user_plan-id_user');
            $table->bigInteger('id_service')->nullable()->index('fk-user_plan-id_service');
            $table->bigInteger('id_invoice_line')->nullable()->index('fk-user_plan-id_invoice_line');
            $table->boolean('is_primary')->default(true);
            $table->integer('alert')->nullable()->default(0);
            $table->integer('max_phone_cnt')->nullable();
            $table->tinyInteger('pay_interval')->nullable();
            $table->decimal('price_before_prorate', 10)->nullable();
            $table->decimal('price_after_prorate', 10)->nullable();
            $table->json('settings')->nullable();
            $table->dateTime('begin_at');
            $table->dateTime('end_at')->nullable();
            $table->tinyInteger('devices')->default(0);
            $table->boolean('is_new_custom')->default(false);
            $table->boolean('is_not_receive_message')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_plan');
    }
};
