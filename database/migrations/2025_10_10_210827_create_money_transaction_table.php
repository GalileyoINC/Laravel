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
        Schema::create('money_transaction', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_user')->index('fk-money_transaction-id_user');
            $table->bigInteger('id_invoice')->nullable()->index('fk-money_transaction-id_invoice');
            $table->bigInteger('id_credit_card')->nullable()->index('fk-money_transaction-id_credit_card');
            $table->tinyInteger('transaction_type');
            $table->string('transaction_id')->nullable();
            $table->boolean('is_success')->nullable()->default(false);
            $table->decimal('total', 10)->default(0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->boolean('is_void')->default(false);
            $table->bigInteger('id_refund')->nullable()->index('fk-money_transaction-id_refund');
            $table->boolean('is_test')->default(false);
            $table->string('error')->nullable();
            $table->text('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('money_transaction');
    }
};
