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
        Schema::table('sms_shedule', function (Blueprint $table) {
            $table->foreign(['id_assistant'], 'fk-sms_shedule-id_assistant')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_follower_list'], 'fk-sms_shedule-id_follower_list')->references(['id'])->on('follower_list')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_sms_pool'], 'fk-sms_shedule-id_sms_pool')->references(['id'])->on('sms_pool')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_staff'], 'fk-sms_shedule-id_staff')->references(['id'])->on('staff')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_subscription'], 'fk-sms_shedule-id_subscription')->references(['id'])->on('subscription')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user'], 'fk-sms_shedule-id_user')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms_shedule', function (Blueprint $table) {
            $table->dropForeign('fk-sms_shedule-id_assistant');
            $table->dropForeign('fk-sms_shedule-id_follower_list');
            $table->dropForeign('fk-sms_shedule-id_sms_pool');
            $table->dropForeign('fk-sms_shedule-id_staff');
            $table->dropForeign('fk-sms_shedule-id_subscription');
            $table->dropForeign('fk-sms_shedule-id_user');
        });
    }
};
