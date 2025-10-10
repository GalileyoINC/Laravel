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
        Schema::table('follower', function (Blueprint $table) {
            $table->foreign(['id_follower_list'], 'fk-follower-id_follower_list')->references(['id'])->on('follower_list')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user_follower'], 'fk-follower-id_user_follower')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user_leader'], 'fk-follower-id_user_leader')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('follower', function (Blueprint $table) {
            $table->dropForeign('fk-follower-id_follower_list');
            $table->dropForeign('fk-follower-id_user_follower');
            $table->dropForeign('fk-follower-id_user_leader');
        });
    }
};
