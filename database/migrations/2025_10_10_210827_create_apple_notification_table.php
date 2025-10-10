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
        Schema::create('apple_notification', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('notification_type')->nullable();
            $table->text('transaction_info')->nullable();
            $table->text('renewal_info')->nullable();
            $table->mediumText('payload')->nullable();
            $table->mediumText('data')->nullable();
            $table->dateTime('created_at');
            $table->string('transaction_id')->nullable();
            $table->string('original_transaction_id')->nullable();
            $table->boolean('is_process')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apple_notification');
    }
};
