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
        Schema::create('follower_list', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_user')->index('fk-follower_list-id_user');
            $table->string('name');
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->string('token')->nullable();
            $table->string('description')->nullable();
            $table->string('image', 63)->nullable();
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follower_list');
    }
};
