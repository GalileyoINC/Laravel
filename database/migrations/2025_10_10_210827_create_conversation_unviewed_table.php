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
        Schema::create('conversation_unviewed', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_user')->nullable();
            $table->bigInteger('id_conversation')->nullable()->index('fk-conversation_unviewed-id_conversation');
            $table->bigInteger('id_conversation_message')->nullable()->index('fk-conversation_unviewed-id_conversation_message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_unviewed');
    }
};
