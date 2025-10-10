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
        Schema::create('invoice_line', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_invoice')->nullable()->index('fk-invoice_line-id_invoice');
            $table->tinyInteger('type')->default(0);
            $table->decimal('total', 10)->nullable()->default(0);
            $table->json('settings')->nullable();
            $table->bigInteger('id_service')->nullable();
            $table->tinyInteger('pay_interval')->nullable();
            $table->integer('quantity')->nullable();
            $table->bigInteger('id_contract_line')->nullable()->index('fk-invoice_line-id_contract_line');
            $table->date('begin_at')->nullable();
            $table->date('end_at')->nullable();
            $table->bigInteger('id_bundle')->nullable()->index('fk-invoice_line-id_bundle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_line');
    }
};
