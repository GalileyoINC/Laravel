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
        Schema::table('comment', function (Blueprint $table) {
            $table->foreign(['id_parent'], 'fk-comment-id_parent')->references(['id'])->on('comment')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_sms_pool'], 'fk-comment-id_sms_pool')->references(['id'])->on('sms_pool')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user'], 'fk-comment-id_user')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comment', function (Blueprint $table) {
            $table->dropForeign('fk-comment-id_parent');
            $table->dropForeign('fk-comment-id_sms_pool');
            $table->dropForeign('fk-comment-id_user');
        });
    }
};
