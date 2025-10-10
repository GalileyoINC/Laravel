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
        Schema::create('subscription_category', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name');
            $table->bigInteger('id_parent')->nullable();
            $table->bigInteger('position_no')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_category');
    }
};
