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
        Schema::table('subscription', function (Blueprint $table) {
            $table->foreign(['id_influencer'], 'fk-subscription-id_influencer')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_subscription_category'], 'fk-subscription-id_subscription_category')->references(['id'])->on('subscription_category')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription', function (Blueprint $table) {
            $table->dropForeign('fk-subscription-id_influencer');
            $table->dropForeign('fk-subscription-id_subscription_category');
        });
    }
};
