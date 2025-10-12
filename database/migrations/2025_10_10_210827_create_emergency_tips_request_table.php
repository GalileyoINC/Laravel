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
        Schema::create('emergency_tips_request', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('first_name', 50)->nullable();
            $table->string('email', 100)->nullable()->unique('uk_emergency_tips_request_email');
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_tips_request');
    }
};
