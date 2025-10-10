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
        Schema::create('invoice_service', function (Blueprint $table) {
            $table->bigInteger('id_invoice');
            $table->bigInteger('id_service')->index('fk_invoice_service_id_service');

            $table->index(['id_invoice', 'id_service'], 'idx_invoice_service');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_service');
    }
};
