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
        Schema::create('conversation_message', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_conversation')->nullable()->index('fk-conversation_message-id_conversation');
            $table->bigInteger('id_user')->nullable()->index('fk-conversation_message-id_user');
            $table->text('message')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('received_at')->nullable();
            $table->string('token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_message');
    }
};
