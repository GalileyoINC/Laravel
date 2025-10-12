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
        Schema::table('admin_member', function (Blueprint $table) {
            $table->foreign(['id_admin'], 'FK_admin_member_id_admin')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_member'], 'FK_admin_member_id_member')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_plan'], 'FK_admin_member_id_plan')->references(['id'])->on('user_plan')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_member', function (Blueprint $table) {
            $table->dropForeign('FK_admin_member_id_admin');
            $table->dropForeign('FK_admin_member_id_member');
            $table->dropForeign('FK_admin_member_id_plan');
        });
    }
};
