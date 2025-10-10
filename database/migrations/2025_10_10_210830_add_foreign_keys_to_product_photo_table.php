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
        Schema::table('product_photo', function (Blueprint $table) {
            $table->foreign(['id_service'], 'fk-product_photo-id_service')->references(['id'])->on('service')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_photo', function (Blueprint $table) {
            $table->dropForeign('fk-product_photo-id_service');
        });
    }
};
