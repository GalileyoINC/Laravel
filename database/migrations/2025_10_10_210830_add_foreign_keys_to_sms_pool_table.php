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
        Schema::table('sms_pool', function (Blueprint $table) {
            $table->foreign(['id_assistant'], 'fk-sms_pool-id_assistant')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_staff'], 'fk-sms_pool-id_staff')->references(['id'])->on('staff')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user'], 'fk-sms_pool-id_user')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms_pool', function (Blueprint $table) {
            $table->dropForeign('fk-sms_pool-id_assistant');
            $table->dropForeign('fk-sms_pool-id_staff');
            $table->dropForeign('fk-sms_pool-id_user');
        });
    }
};
