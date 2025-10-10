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
        Schema::create('sps_contract', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_user')->index('fk_sps_contract_id_user');
            $table->bigInteger('id_contract')->unique('id_contract');
            $table->bigInteger('id_plan')->nullable()->index('fk_sps_contract_id_plan');
            $table->bigInteger('id_service')->index('fk_sps_contract_id_service');
            $table->integer('alert');
            $table->integer('max_phone_cnt');
            $table->integer('pay_interval');
            $table->dateTime('begin_at');
            $table->dateTime('ended_at');
            $table->dateTime('terminated_at')->nullable();
            $table->tinyInteger('is_secondary')->nullable()->default(0);
            $table->json('user_plan_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sps_contract');
    }
};
