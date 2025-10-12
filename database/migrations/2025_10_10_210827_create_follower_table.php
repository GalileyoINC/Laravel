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
        Schema::create('follower', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_follower_list')->index('fk-follower-id_follower_list');
            $table->bigInteger('id_user_leader')->index('fk-follower-id_user_leader');
            $table->bigInteger('id_user_follower')->index('fk-follower-id_user_follower');
            $table->boolean('is_active')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->json('invite_settings')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follower');
    }
};
