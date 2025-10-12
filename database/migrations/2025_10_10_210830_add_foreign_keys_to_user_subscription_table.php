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
        Schema::table('user_subscription', function (Blueprint $table) {
            $table->foreign(['id_subscription'], 'fk-user_subscription-id_subscription')->references(['id'])->on('subscription')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user'], 'fk-user_subscription-id_user')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_subscription', function (Blueprint $table) {
            $table->dropForeign('fk-user_subscription-id_subscription');
            $table->dropForeign('fk-user_subscription-id_user');
        });
    }
};
