<?php

declare(strict_types=1);

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
        Schema::table('contract_line_paid', function (Blueprint $table) {
            $table->foreign(['id_contract_line'], 'fk-contract_line_paid-id_contract_line')->references(['id'])->on('contract_line')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_invoice'], 'fk-contract_line_paid-id_invoice')->references(['id'])->on('invoice')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_invoice_line'], 'fk-contract_line_paid-id_invoice_line')->references(['id'])->on('invoice_line')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_line_paid', function (Blueprint $table) {
            $table->dropForeign('fk-contract_line_paid-id_contract_line');
            $table->dropForeign('fk-contract_line_paid-id_invoice');
            $table->dropForeign('fk-contract_line_paid-id_invoice_line');
        });
    }
};
