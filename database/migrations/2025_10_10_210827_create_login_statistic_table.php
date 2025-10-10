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
        Schema::create('login_statistic', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_user')->index('fk-login_statistic-id_user');
            $table->bigInteger('id_device')->nullable();
            $table->tinyInteger('type');
            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('data')->nullable();
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_statistic');
    }
};
