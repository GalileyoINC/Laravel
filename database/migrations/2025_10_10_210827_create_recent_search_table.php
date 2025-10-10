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
        Schema::create('recent_search', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('id_user');
            $table->string('phrase', 25)->nullable();
            $table->bigInteger('id_search_user')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recent_search');
    }
};
