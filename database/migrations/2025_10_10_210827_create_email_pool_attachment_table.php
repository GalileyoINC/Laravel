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
        Schema::create('email_pool_attachment', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_email_pool')->index('fk_email_pool_attachment_email_pool_id');
            $table->binary('body');
            $table->string('file_name');
            $table->string('content_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_pool_attachment');
    }
};
