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
        Schema::create('iex_webhook', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('iex_id')->nullable();
            $table->string('event')->nullable();
            $table->string('set')->nullable();
            $table->string('name')->nullable();
            $table->json('data')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iex_webhook');
    }
};
