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
        Schema::create('influencer_assistant', function (Blueprint $table) {
            $table->bigInteger('id_influencer');
            $table->bigInteger('id_assistant')->index('fk-influencer_assistant-id_assistant');
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();

            $table->primary(['id_influencer', 'id_assistant']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('influencer_assistant');
    }
};
