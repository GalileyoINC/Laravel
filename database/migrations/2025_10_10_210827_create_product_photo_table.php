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
        Schema::create('product_photo', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_service')->nullable()->index('fk-product_photo-id_service');
            $table->string('folder_name')->nullable();
            $table->json('sizes')->nullable();
            $table->boolean('is_main')->nullable()->default(false);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_photo');
    }
};
