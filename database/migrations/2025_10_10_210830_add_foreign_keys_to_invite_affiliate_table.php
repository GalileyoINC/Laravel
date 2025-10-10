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
        Schema::table('invite_affiliate', function (Blueprint $table) {
            $table->foreign(['id_invite_invoice'], 'fk-invite_affiliate-id_invite_invoice')->references(['id'])->on('invoice')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_invited'], 'fk-invite_affiliate-id_invited')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_inviter'], 'fk-invite_affiliate-id_inviter')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_reward_invoice'], 'fk-invite_affiliate-id_reward_invoice')->references(['id'])->on('invoice')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invite_affiliate', function (Blueprint $table) {
            $table->dropForeign('fk-invite_affiliate-id_invite_invoice');
            $table->dropForeign('fk-invite_affiliate-id_invited');
            $table->dropForeign('fk-invite_affiliate-id_inviter');
            $table->dropForeign('fk-invite_affiliate-id_reward_invoice');
        });
    }
};
