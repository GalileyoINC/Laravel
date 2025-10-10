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
        Schema::table('user_service', function (Blueprint $table) {
            $table->foreign(['id_service'], 'FK_user_service_id_service')->references(['id'])->on('service')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user'], 'FK_user_service_id_user')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_service', function (Blueprint $table) {
            $table->dropForeign('FK_user_service_id_service');
            $table->dropForeign('FK_user_service_id_user');
        });
    }
};
