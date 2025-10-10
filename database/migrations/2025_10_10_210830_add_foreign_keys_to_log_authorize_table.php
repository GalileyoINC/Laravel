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
        Schema::table('log_authorize', function (Blueprint $table) {
            $table->foreign(['id_money_transaction'], 'fk-log_authorize-id_money_transaction')->references(['id'])->on('money_transaction')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user'], 'fk-log_authorize-id_user')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_authorize', function (Blueprint $table) {
            $table->dropForeign('fk-log_authorize-id_money_transaction');
            $table->dropForeign('fk-log_authorize-id_user');
        });
    }
};
