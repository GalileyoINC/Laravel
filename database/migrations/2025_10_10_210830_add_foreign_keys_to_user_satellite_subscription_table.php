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
        Schema::table('user_satellite_subscription', function (Blueprint $table) {
            $table->foreign(['id_subscription'], 'fk-user_satellite_subscription-id_subscription')->references(['id'])->on('subscription')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user'], 'fk-user_satellite_subscription-id_user')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_satellite_subscription', function (Blueprint $table) {
            $table->dropForeign('fk-user_satellite_subscription-id_subscription');
            $table->dropForeign('fk-user_satellite_subscription-id_user');
        });
    }
};
