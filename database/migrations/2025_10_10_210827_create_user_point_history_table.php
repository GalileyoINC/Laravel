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
        Schema::create('user_point_history', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_user')->index('fk-user_point_history-id_user');
            $table->bigInteger('id_user_point_settings')->index('fk-user_point_history-id_user_point_settings');
            $table->bigInteger('id_sms_pool')->nullable()->index('fk-user_point_history-id_sms_pool');
            $table->bigInteger('id_comment')->nullable()->index('fk-user_point_history-id_comment');
            $table->integer('quantity');
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_point_history');
    }
};
