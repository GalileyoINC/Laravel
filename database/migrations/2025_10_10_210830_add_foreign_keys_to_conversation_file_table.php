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
        Schema::table('conversation_file', function (Blueprint $table) {
            $table->foreign(['id_conversation'], 'fk-conversation_file-id_conversation')->references(['id'])->on('conversation')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_conversation_message'], 'fk-conversation_file-id_conversation_message')->references(['id'])->on('conversation_message')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversation_file', function (Blueprint $table) {
            $table->dropForeign('fk-conversation_file-id_conversation');
            $table->dropForeign('fk-conversation_file-id_conversation_message');
        });
    }
};
