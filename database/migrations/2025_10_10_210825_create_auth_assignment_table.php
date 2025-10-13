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
        Schema::create('auth_assignment', function (Blueprint $table) {
            $table->string('item_name', 64);
            $table->string('user_id', 64)->index('idx-auth_assignment-user_id');
            $table->integer('created_at')->nullable();

            $table->primary(['item_name', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_assignment');
    }
};
