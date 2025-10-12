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
        Schema::create('sms_pool_report', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('influencer_min')->nullable();
            $table->integer('influencer_max')->nullable();
            $table->decimal('influencer_avg', 10)->nullable();
            $table->decimal('influencer_median', 10)->nullable();
            $table->integer('influencer_total')->nullable();
            $table->integer('influencer_users')->nullable();
            $table->integer('api_min')->nullable();
            $table->integer('api_max')->nullable();
            $table->decimal('api_avg', 10)->nullable();
            $table->decimal('api_median', 10)->nullable();
            $table->integer('api_total')->nullable();
            $table->integer('api_users')->nullable();
            $table->date('day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_pool_report');
    }
};
