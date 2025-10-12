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
        Schema::create('contract_line_paid', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_contract_line')->nullable()->index('fk-contract_line_paid-id_contract_line');
            $table->bigInteger('id_invoice')->nullable()->index('fk-contract_line_paid-id_invoice');
            $table->bigInteger('id_invoice_line')->nullable()->index('fk-contract_line_paid-id_invoice_line');
            $table->decimal('total', 10)->default(0);
            $table->date('begin_at')->nullable();
            $table->date('end_at')->nullable();
            $table->integer('days')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_line_paid');
    }
};
