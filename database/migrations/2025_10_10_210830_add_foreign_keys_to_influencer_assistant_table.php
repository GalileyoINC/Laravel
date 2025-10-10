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
        Schema::table('influencer_assistant', function (Blueprint $table) {
            $table->foreign(['id_assistant'], 'fk-influencer_assistant-id_assistant')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_influencer'], 'fk-influencer_assistant-id_influencer')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('influencer_assistant', function (Blueprint $table) {
            $table->dropForeign('fk-influencer_assistant-id_assistant');
            $table->dropForeign('fk-influencer_assistant-id_influencer');
        });
    }
};
