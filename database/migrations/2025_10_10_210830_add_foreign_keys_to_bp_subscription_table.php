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
        Schema::table('bp_subscription', function (Blueprint $table) {
            $table->foreign(['id_user'], 'FK_bp_subscription_id_user')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bp_subscription', function (Blueprint $table) {
            $table->dropForeign('FK_bp_subscription_id_user');
        });
    }
};
