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
        Schema::table('device_plan', function (Blueprint $table) {
            // Drop wrong FK to service
            if ($this->foreignKeyExists('device_plan', 'fk-device_plan-id_device')) {
                $table->dropForeign('fk-device_plan-id_device');
            }

            // Add correct FK to device
            $table->foreign(['id_device'], 'fk-device_plan-id_device')
                ->references(['id'])->on('device')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_plan', function (Blueprint $table) {
            // Drop FK to device
            if ($this->foreignKeyExists('device_plan', 'fk-device_plan-id_device')) {
                $table->dropForeign('fk-device_plan-id_device');
            }

            // Restore original (wrong) FK to service to keep down() reversible
            $table->foreign(['id_device'], 'fk-device_plan-id_device')
                ->references(['id'])->on('service')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    private function foreignKeyExists(string $table, string $indexName): bool
    {
        // Works across MySQL/MariaDB; safe fallback if not present
        try {
            $connection = Schema::getConnection();
            $schema = $connection->getDoctrineSchemaManager();
            $doctrineTable = $schema->listTableDetails($table);

            return $doctrineTable->hasForeignKey($indexName);
        } catch (Throwable $e) {
            // If doctrine not available, attempt raw check
            try {
                $dbName = $connection->getDatabaseName();
                $count = $connection->selectOne(
                    'SELECT COUNT(*) AS cnt FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = "FOREIGN KEY"',
                    [$dbName, $table, $indexName]
                );

                return ((int) ($count->cnt ?? 0)) > 0;
            } catch (Throwable) {
                return false;
            }
        }
    }
};
