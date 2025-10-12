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
        Schema::create('conversation_file', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_conversation')->nullable()->index('fk-conversation_file-id_conversation');
            $table->bigInteger('id_conversation_message')->nullable()->index('fk-conversation_file-id_conversation_message');
            $table->string('folder_name')->nullable();
            $table->string('web_name')->nullable();
            $table->string('file_name')->nullable();
            $table->json('sizes')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_file');
    }
};
