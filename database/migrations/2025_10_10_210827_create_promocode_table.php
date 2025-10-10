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
        Schema::create('promocode', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->tinyInteger('type');
            $table->string('text', 25)->unique('uk_promocode_text');
            $table->integer('discount');
            $table->tinyInteger('is_active')->default(1);
            $table->dateTime('active_from');
            $table->dateTime('active_to');
            $table->integer('trial_period')->nullable();
            $table->boolean('show_on_frontend')->default(false);
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promocode');
    }
};
