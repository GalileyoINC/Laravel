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
        Schema::table('user_plan_shedule', function (Blueprint $table) {
            $table->foreign(['id_contract_line'], 'fk-user_plan_shedule-id_contract_line')->references(['id'])->on('contract_line')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_service'], 'FK_user_plan_shedule_id_service')->references(['id'])->on('service')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_user'], 'FK_user_plan_shedule_id_user')->references(['id'])->on('user')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_plan_shedule', function (Blueprint $table) {
            $table->dropForeign('fk-user_plan_shedule-id_contract_line');
            $table->dropForeign('FK_user_plan_shedule_id_service');
            $table->dropForeign('FK_user_plan_shedule_id_user');
        });
    }
};
