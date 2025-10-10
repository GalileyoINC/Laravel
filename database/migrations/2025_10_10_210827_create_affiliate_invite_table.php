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
        Schema::create('affiliate_invite', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_user')->index('fk-affiliate_invite-id_user');
            $table->string('email');
            $table->string('phone_number', 63)->nullable();
            $table->string('token')->nullable();
            $table->json('params')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_invite');
    }
};
