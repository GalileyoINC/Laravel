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
        Schema::table('influencer_page', function (Blueprint $table) {
            $table->foreign(['id_subscription'], 'fk-influencer_page-id_subscription')->references(['id'])->on('subscription')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('influencer_page', function (Blueprint $table) {
            $table->dropForeign('fk-influencer_page-id_subscription');
        });
    }
};
