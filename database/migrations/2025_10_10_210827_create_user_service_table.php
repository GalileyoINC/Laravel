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
        Schema::create('user_service', function (Blueprint $table) {
            $table->bigInteger('id_user')->nullable()->index('fk_user_service_id_user');
            $table->bigInteger('id_service')->nullable()->index('fk_user_service_id_service');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_service');
    }
};
