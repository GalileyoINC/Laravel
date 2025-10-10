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
        Schema::create('passkey', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name')->nullable();
            $table->text('publicKey');
            $table->bigInteger('userId');
            $table->text('credentialID');
            $table->bigInteger('counter');
            $table->string('deviceType');
            $table->boolean('backedUp');
            $table->text('transports');
            $table->timestamp('createdAt');
            $table->text('aaguid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passkey');
    }
};
