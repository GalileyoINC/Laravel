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
        Schema::create('session', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->timestamp('expiresAt');
            $table->string('token')->unique('token');
            $table->timestamp('createdAt');
            $table->timestamp('updatedAt');
            $table->text('ipAddress')->nullable();
            $table->text('userAgent')->nullable();
            $table->bigInteger('userId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session');
    }
};
