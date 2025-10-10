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
        Schema::table('member_request', function (Blueprint $table) {
            $table->foreign(['id_user'], 'FK_member_request_id_user')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user_from'], 'FK_member_request_id_user_from')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_request', function (Blueprint $table) {
            $table->dropForeign('FK_member_request_id_user');
            $table->dropForeign('FK_member_request_id_user_from');
        });
    }
};
