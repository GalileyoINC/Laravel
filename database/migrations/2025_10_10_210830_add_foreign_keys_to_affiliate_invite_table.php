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
        Schema::table('affiliate_invite', function (Blueprint $table) {
            $table->foreign(['id_user'], 'fk-affiliate_invite-id_user')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliate_invite', function (Blueprint $table) {
            $table->dropForeign('fk-affiliate_invite-id_user');
        });
    }
};
