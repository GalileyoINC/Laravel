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
        Schema::create('marketstack_indx', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name', 64);
            $table->string('symbol', 16);
            $table->string('country', 16)->nullable();
            $table->string('currency', 4)->nullable();
            $table->boolean('has_intraday')->nullable();
            $table->boolean('has_eod')->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            $table->json('full')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketstack_indx');
    }
};
