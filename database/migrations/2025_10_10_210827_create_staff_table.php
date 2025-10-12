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
        Schema::create('staff', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('username')->unique('username');
            $table->string('email')->unique('email');
            $table->string('auth_key', 32);
            $table->string('password_hash');
            $table->string('password_reset_token')->nullable()->unique('password_reset_token');
            $table->tinyInteger('role')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_superlogin')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
