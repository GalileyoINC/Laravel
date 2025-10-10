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
        Schema::create('bundle_item', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_bundle')->nullable()->index('fk-bundle_item-id_bundle');
            $table->bigInteger('id_item')->nullable()->index('fk-bundle_item-id_item');
            $table->tinyInteger('type')->nullable();
            $table->decimal('price', 10)->default(0);
            $table->integer('quantity')->nullable();
            $table->json('custom_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bundle_item');
    }
};
