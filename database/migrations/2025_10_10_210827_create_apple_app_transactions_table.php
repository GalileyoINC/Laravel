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
        Schema::create('apple_app_transactions', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('transaction_id')->nullable();
            $table->string('status')->nullable();
            $table->string('error')->nullable();
            $table->bigInteger('id_user')->nullable();
            $table->json('data')->nullable();
            $table->dateTime('apple_created_at')->nullable();
            $table->dateTime('created_at');
            $table->boolean('is_process')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apple_app_transactions');
    }
};
