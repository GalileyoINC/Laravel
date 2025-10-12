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
        Schema::create('report_referral', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('period', 14)->nullable()->unique('uk_report_referral_month');
            $table->integer('influencer_percent')->default(0);
            $table->json('data')->nullable();
            $table->dateTime('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_referral');
    }
};
