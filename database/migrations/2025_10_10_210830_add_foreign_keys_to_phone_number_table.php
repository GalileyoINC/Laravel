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
        Schema::table('phone_number', function (Blueprint $table) {
            $table->foreign(['id_provider'], 'fk-phone_number-id_provider')->references(['id'])->on('provider')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user'], 'fk-phone_number-id_user')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phone_number', function (Blueprint $table) {
            $table->dropForeign('fk-phone_number-id_provider');
            $table->dropForeign('fk-phone_number-id_user');
        });
    }
};
