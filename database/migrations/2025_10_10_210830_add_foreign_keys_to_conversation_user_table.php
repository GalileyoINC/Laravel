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
        Schema::table('conversation_user', function (Blueprint $table) {
            $table->foreign(['id_conversation'], 'fk-conversation_user-id_conversation')->references(['id'])->on('conversation')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_user'], 'fk-conversation_user-id_user')->references(['id'])->on('user')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversation_user', function (Blueprint $table) {
            $table->dropForeign('fk-conversation_user-id_conversation');
            $table->dropForeign('fk-conversation_user-id_user');
        });
    }
};
