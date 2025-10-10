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
        Schema::table('sms_pool_reaction', function (Blueprint $table) {
            $table->foreign(['id_reaction'], 'fk-sms_pool_reaction-id_reaction')->references(['id'])->on('reaction')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_sms_pool'], 'fk-sms_pool_reaction-id_sms_pool')->references(['id'])->on('sms_pool')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_user'], 'fk-sms_pool_reaction-id_user')->references(['id'])->on('user')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms_pool_reaction', function (Blueprint $table) {
            $table->dropForeign('fk-sms_pool_reaction-id_reaction');
            $table->dropForeign('fk-sms_pool_reaction-id_sms_pool');
            $table->dropForeign('fk-sms_pool_reaction-id_user');
        });
    }
};
