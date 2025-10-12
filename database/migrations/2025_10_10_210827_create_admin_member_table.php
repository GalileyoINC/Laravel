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
        Schema::create('admin_member', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_admin')->nullable();
            $table->bigInteger('id_member')->nullable()->index('fk_admin_member_id_member');
            $table->bigInteger('id_plan')->nullable()->index('fk_admin_member_id_plan');

            $table->unique(['id_admin', 'id_member'], 'uk_admin_member');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_member');
    }
};
