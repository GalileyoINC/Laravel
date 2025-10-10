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
        Schema::table('user_point_history', function (Blueprint $table) {
            $table->foreign(['id_comment'], 'fk-user_point_history-id_comment')->references(['id'])->on('comment')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_sms_pool'], 'fk-user_point_history-id_sms_pool')->references(['id'])->on('sms_pool')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_user'], 'fk-user_point_history-id_user')->references(['id'])->on('user')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_user_point_settings'], 'fk-user_point_history-id_user_point_settings')->references(['id'])->on('user_point_settings')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_point_history', function (Blueprint $table) {
            $table->dropForeign('fk-user_point_history-id_comment');
            $table->dropForeign('fk-user_point_history-id_sms_pool');
            $table->dropForeign('fk-user_point_history-id_user');
            $table->dropForeign('fk-user_point_history-id_user_point_settings');
        });
    }
};
