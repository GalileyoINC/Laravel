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
        Schema::table('sps_contract', function (Blueprint $table) {
            $table->foreign(['id_plan'], 'FK_sps_contract_id_plan')->references(['id'])->on('user_plan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_service'], 'FK_sps_contract_id_service')->references(['id'])->on('service')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user'], 'FK_sps_contract_id_user')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sps_contract', function (Blueprint $table) {
            $table->dropForeign('FK_sps_contract_id_plan');
            $table->dropForeign('FK_sps_contract_id_service');
            $table->dropForeign('FK_sps_contract_id_user');
        });
    }
};
