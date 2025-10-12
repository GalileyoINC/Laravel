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
        Schema::table('invite', function (Blueprint $table) {
            $table->foreign(['id_follower_list'], 'fk-invite-id_follower_list')->references(['id'])->on('follower_list')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user'], 'fk-invite-id_user')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invite', function (Blueprint $table) {
            $table->dropForeign('fk-invite-id_follower_list');
            $table->dropForeign('fk-invite-id_user');
        });
    }
};
