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
        Schema::create('user_friend', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_user')->nullable();
            $table->bigInteger('id_friend')->nullable()->index('fk_user_friend_id_friend');

            $table->unique(['id_user', 'id_friend'], 'uk_user_friend');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_friend');
    }
};
