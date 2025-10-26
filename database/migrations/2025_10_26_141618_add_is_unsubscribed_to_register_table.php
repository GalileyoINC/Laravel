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
        Schema::table('register', function (Blueprint $table) {
            $table->boolean('is_unsubscribed')->default(false)->after('is_unfinished_signup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('register', function (Blueprint $table) {
            $table->dropColumn('is_unsubscribed');
        });
    }
};
