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
        Schema::table('invoice_service', function (Blueprint $table) {
            $table->foreign(['id_invoice'], 'FK_invoice_service_id_invoice')->references(['id'])->on('invoice')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_service'], 'FK_invoice_service_id_service')->references(['id'])->on('service')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_service', function (Blueprint $table) {
            $table->dropForeign('FK_invoice_service_id_invoice');
            $table->dropForeign('FK_invoice_service_id_service');
        });
    }
};
