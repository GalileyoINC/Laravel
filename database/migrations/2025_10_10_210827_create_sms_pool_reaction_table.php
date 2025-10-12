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
        Schema::create('sms_pool_reaction', function (Blueprint $table) {
            $table->bigInteger('id_sms_pool');
            $table->bigInteger('id_user')->index('fk-sms_pool_reaction-id_user');
            $table->bigInteger('id_reaction')->nullable()->index('fk-sms_pool_reaction-id_reaction');
            $table->dateTime('created_at');

            $table->primary(['id_sms_pool', 'id_user']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_pool_reaction');
    }
};
