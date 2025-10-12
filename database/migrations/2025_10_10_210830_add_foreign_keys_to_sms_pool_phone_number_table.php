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
        Schema::table('sms_pool_phone_number', function (Blueprint $table) {
            $table->foreign(['id_phone_number'], 'fk-sms_pool_phone_number-id_phone_number')->references(['id'])->on('phone_number')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_provider'], 'fk-sms_pool_phone_number-id_provider')->references(['id'])->on('provider')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_sms_pool'], 'fk-sms_pool_phone_number-id_sms_pool')->references(['id'])->on('sms_pool')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user'], 'fk-sms_pool_phone_number-id_user')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms_pool_phone_number', function (Blueprint $table) {
            $table->dropForeign('fk-sms_pool_phone_number-id_phone_number');
            $table->dropForeign('fk-sms_pool_phone_number-id_provider');
            $table->dropForeign('fk-sms_pool_phone_number-id_sms_pool');
            $table->dropForeign('fk-sms_pool_phone_number-id_user');
        });
    }
};
