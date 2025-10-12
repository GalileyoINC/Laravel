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
        Schema::table('promocode_influencer', function (Blueprint $table) {
            $table->foreign(['id_promocode'], 'fk-promocode_influencer-id_promocode')->references(['id'])->on('promocode')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promocode_influencer', function (Blueprint $table) {
            $table->dropForeign('fk-promocode_influencer-id_promocode');
        });
    }
};
