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
        Schema::create('logger', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('employee_login', 100)->nullable()->index('idx_logger_employee_login');
            $table->string('employee_first_name', 50)->nullable()->index('idx_logger_employee_first_name');
            $table->string('employee_last_name', 50)->nullable();
            $table->tinyInteger('access_level')->nullable()->index('idx_logger_access_level');
            $table->timestamp('created_at')->nullable()->index('idx_logger_created_at');
            $table->string('category', 50)->nullable()->index('idx_logger_category');
            $table->string('error_type', 20)->nullable()->comment('Error/Warning/Info');
            $table->text('source')->nullable();
            $table->text('content')->nullable();
            $table->string('module', 100)->nullable()->index('idx_logger_module');
            $table->string('controller', 50)->nullable()->index('idx_logger_controller');
            $table->string('action', 50)->nullable()->index('idx_logger_action');
            $table->string('ip', 39)->nullable()->index('idx_logger_ip');
            $table->json('primary_json')->nullable();
            $table->string('user_agent')->nullable();
            $table->json('changed_fields')->nullable();

            $table->index(['error_type', 'created_at'], 'idx_logger_error_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logger');
    }
};
