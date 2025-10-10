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
        Schema::create('service_custom', function (Blueprint $table) {
            $table->integer('id', true);
            $table->float('phone_price');
            $table->float('feed_price');
            $table->integer('phone_min');
            $table->integer('phone_max');
            $table->integer('feed_min');
            $table->integer('feed_max');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_custom');
    }
};
