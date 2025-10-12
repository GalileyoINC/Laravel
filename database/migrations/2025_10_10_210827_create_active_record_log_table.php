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
        Schema::create('active_record_log', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_user')->nullable();
            $table->bigInteger('id_staff')->nullable();
            $table->tinyInteger('action_type')->nullable();
            $table->string('model', 50)->nullable();
            $table->string('id_model')->nullable();
            $table->string('field', 50)->nullable();
            $table->json('changes')->nullable();
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('active_record_log');
    }
};
