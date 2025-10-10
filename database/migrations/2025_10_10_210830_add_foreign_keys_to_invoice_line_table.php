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
        Schema::table('invoice_line', function (Blueprint $table) {
            $table->foreign(['id_bundle'], 'fk-invoice_line-id_bundle')->references(['id'])->on('bundle')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_contract_line'], 'fk-invoice_line-id_contract_line')->references(['id'])->on('contract_line')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_invoice'], 'fk-invoice_line-id_invoice')->references(['id'])->on('invoice')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_line', function (Blueprint $table) {
            $table->dropForeign('fk-invoice_line-id_bundle');
            $table->dropForeign('fk-invoice_line-id_contract_line');
            $table->dropForeign('fk-invoice_line-id_invoice');
        });
    }
};
