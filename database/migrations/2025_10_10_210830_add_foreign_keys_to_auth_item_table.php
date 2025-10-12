<?php

declare(strict_types=1);

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
        Schema::table('auth_item', function (Blueprint $table) {
            $table->foreign(['rule_name'], 'auth_item_ibfk_1')->references(['name'])->on('auth_rule')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auth_item', function (Blueprint $table) {
            $table->dropForeign('auth_item_ibfk_1');
        });
    }
};
