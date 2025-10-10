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
        Schema::table('auth_assignment', function (Blueprint $table) {
            $table->foreign(['item_name'], 'auth_assignment_ibfk_1')->references(['name'])->on('auth_item')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auth_assignment', function (Blueprint $table) {
            $table->dropForeign('auth_assignment_ibfk_1');
        });
    }
};
