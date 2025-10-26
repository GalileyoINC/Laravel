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
        // Ensure the base table exists before attempting to add constraints
        if (! Schema::hasTable('money_transaction')) {
            return;
        }

        Schema::table('money_transaction', function (Blueprint $table) {
            // id_credit_card -> credit_cards.id (only if credit_cards exists)
            if (
                Schema::hasTable('credit_cards') &&
                Schema::hasColumn('money_transaction', 'id_credit_card') &&
                ! $this->foreignKeyExists('money_transaction', 'fk-money_transaction-id_credit_card')
            ) {
                $table->foreign(['id_credit_card'], 'fk-money_transaction-id_credit_card')
                    ->references(['id'])->on('credit_cards')
                    ->onUpdate('cascade')->onDelete('cascade');
            }

            // id_invoice -> invoice.id
            if (
                Schema::hasTable('invoice') &&
                Schema::hasColumn('money_transaction', 'id_invoice') &&
                ! $this->foreignKeyExists('money_transaction', 'fk-money_transaction-id_invoice')
            ) {
                $table->foreign(['id_invoice'], 'fk-money_transaction-id_invoice')
                    ->references(['id'])->on('invoice')
                    ->onUpdate('cascade')->onDelete('cascade');
            }

            // id_refund -> money_transaction.id (self-referencing)
            if (
                Schema::hasColumn('money_transaction', 'id_refund') &&
                ! $this->foreignKeyExists('money_transaction', 'fk-money_transaction-id_refund')
            ) {
                $table->foreign(['id_refund'], 'fk-money_transaction-id_refund')
                    ->references(['id'])->on('money_transaction')
                    ->onUpdate('cascade')->onDelete('cascade');
            }

            // id_user -> user.id
            if (
                Schema::hasTable('user') &&
                Schema::hasColumn('money_transaction', 'id_user') &&
                ! $this->foreignKeyExists('money_transaction', 'fk-money_transaction-id_user')
            ) {
                $table->foreign(['id_user'], 'fk-money_transaction-id_user')
                    ->references(['id'])->on('user')
                    ->onUpdate('cascade')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('money_transaction')) {
            return;
        }

        Schema::table('money_transaction', function (Blueprint $table) {
            // Drop FKs only if they exist
            foreach ([
                'fk-money_transaction-id_credit_card',
                'fk-money_transaction-id_invoice',
                'fk-money_transaction-id_refund',
                'fk-money_transaction-id_user',
            ] as $fkName) {
                if ($this->foreignKeyExists('money_transaction', $fkName)) {
                    $table->dropForeign($fkName);
                }
            }
        });
    }

    /**
     * Check if a foreign key constraint exists
     */
    private function foreignKeyExists(string $table, string $constraintName): bool
    {
        try {
            $constraints = \DB::select(
                "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME = ? AND CONSTRAINT_NAME = ?",
                [$table, $constraintName]
            );

            return count($constraints) > 0;
        } catch (\Exception $e) {
            // If we can't check, assume it doesn't exist
            return false;
        }
    }
};
