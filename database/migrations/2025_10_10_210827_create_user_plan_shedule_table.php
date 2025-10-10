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
        Schema::create('user_plan_shedule', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_user')->nullable()->index('fk_user_plan_shedule_id_user');
            $table->bigInteger('id_service')->nullable()->index('fk_user_plan_shedule_id_service');
            $table->json('settings')->nullable();
            $table->dateTime('begin_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->boolean('is_complete')->default(false);
            $table->boolean('is_new_custom')->default(false);
            $table->bigInteger('id_contract_line')->nullable()->index('fk-user_plan_shedule-id_contract_line');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_plan_shedule');
    }
};
