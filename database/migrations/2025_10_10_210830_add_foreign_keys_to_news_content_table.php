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
        Schema::table('news_content', function (Blueprint $table) {
            $table->foreign(['id_news'], 'fk-news_content-id_news')->references(['id'])->on('news')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news_content', function (Blueprint $table) {
            $table->dropForeign('fk-news_content-id_news');
        });
    }
};
