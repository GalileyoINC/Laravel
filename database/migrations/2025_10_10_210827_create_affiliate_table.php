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
        Schema::create('affiliate', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_user_parent')->index('fk-affiliate-id_user_parent');
            $table->bigInteger('id_user_child')->index('fk-affiliate-id_user_child');
            $table->boolean('is_active')->nullable();
            $table->json('params')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate');
    }
};
