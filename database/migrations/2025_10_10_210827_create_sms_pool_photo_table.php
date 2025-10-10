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
        Schema::create('sms_pool_photo', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_sms_pool')->nullable()->index('fk-sms_pool_photo-id_sms_pool');
            $table->bigInteger('id_sms_shedule')->nullable()->index('fk-sms_pool_photo-id_sms_shedule');
            $table->string('folder_name')->nullable();
            $table->string('web_name')->nullable();
            $table->json('sizes')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->string('uuid', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_pool_photo');
    }
};
