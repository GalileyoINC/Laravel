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
        Schema::create('invite_affiliate', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_inviter')->index('fk-invite_affiliate-id_inviter');
            $table->bigInteger('id_invited')->index('fk-invite_affiliate-id_invited');
            $table->bigInteger('id_invite_invoice')->index('fk-invite_affiliate-id_invite_invoice');
            $table->bigInteger('id_reward_invoice')->nullable()->index('fk-invite_affiliate-id_reward_invoice');
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invite_affiliate');
    }
};
