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
        Schema::create('account', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->text('accountId');
            $table->text('providerId');
            $table->bigInteger('userId');
            $table->text('accessToken')->nullable();
            $table->text('refreshToken')->nullable();
            $table->text('idToken')->nullable();
            $table->timestamp('accessTokenExpiresAt')->nullable();
            $table->timestamp('refreshTokenExpiresAt')->nullable();
            $table->text('scope')->nullable();
            $table->text('password')->nullable();
            $table->timestamp('createdAt');
            $table->timestamp('updatedAt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account');
    }
};
