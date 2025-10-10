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
        Schema::create('member_template', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_admin')->nullable()->index('fk_member_template_id_user');
            $table->string('first_name', 50)->nullable();
            $table->string('last_name')->nullable();
            $table->bigInteger('id_plan')->nullable()->index('fk_member_template_id_plan');
            $table->string('email', 50)->nullable();
            $table->string('member_key')->nullable();
            $table->dateTime('expired_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_template');
    }
};
