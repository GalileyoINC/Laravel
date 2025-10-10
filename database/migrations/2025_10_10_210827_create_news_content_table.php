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
        Schema::create('news_content', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_news')->nullable()->index('fk-news_content-id_news');
            $table->tinyInteger('status')->default(0);
            $table->json('params')->nullable();
            $table->longText('content')->nullable();
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_content');
    }
};
