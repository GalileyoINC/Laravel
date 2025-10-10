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
        Schema::table('contract_line', function (Blueprint $table) {
            $table->foreign(['id_service'], 'fk-contract_line-id_service')->references(['id'])->on('service')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_sps_contract'], 'fk-contract_line-id_sps_contract')->references(['id'])->on('sps_contract')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_user'], 'fk-contract_line-id_user')->references(['id'])->on('user')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_line', function (Blueprint $table) {
            $table->dropForeign('fk-contract_line-id_service');
            $table->dropForeign('fk-contract_line-id_sps_contract');
            $table->dropForeign('fk-contract_line-id_user');
        });
    }
};
