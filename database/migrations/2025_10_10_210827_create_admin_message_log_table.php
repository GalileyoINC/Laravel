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
        Schema::create('admin_message_log', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_staff');
            $table->tinyInteger('obj_type')->nullable();
            $table->string('obj_id', 10)->nullable();
            $table->text('body')->nullable();
            $table->dateTime('created_at')->nullable();

            $table->index(['obj_type', 'obj_id', 'created_at'], 'idx_admin_message_log');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_message_log');
    }
};
