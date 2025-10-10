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
        Schema::table('sms_pool_photo', function (Blueprint $table) {
            $table->foreign(['id_sms_pool'], 'fk-sms_pool_photo-id_sms_pool')->references(['id'])->on('sms_pool')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_sms_shedule'], 'fk-sms_pool_photo-id_sms_shedule')->references(['id'])->on('sms_shedule')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms_pool_photo', function (Blueprint $table) {
            $table->dropForeign('fk-sms_pool_photo-id_sms_pool');
            $table->dropForeign('fk-sms_pool_photo-id_sms_shedule');
        });
    }
};
