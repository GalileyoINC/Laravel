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
        Schema::table('bundle_item', function (Blueprint $table) {
            $table->foreign(['id_bundle'], 'fk-bundle_item-id_bundle')->references(['id'])->on('bundle')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_item'], 'fk-bundle_item-id_item')->references(['id'])->on('service')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bundle_item', function (Blueprint $table) {
            $table->dropForeign('fk-bundle_item-id_bundle');
            $table->dropForeign('fk-bundle_item-id_item');
        });
    }
};
