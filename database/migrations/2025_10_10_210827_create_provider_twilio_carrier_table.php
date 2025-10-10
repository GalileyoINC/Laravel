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
        Schema::create('provider_twilio_carrier', function (Blueprint $table) {
            $table->bigInteger('id_provider')->index('fk-provider_twilio_carrier-id_provider');
            $table->bigInteger('id_twilio_carrier')->index('fk-provider_twilio_carrier-id_twilio_carrier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_twilio_carrier');
    }
};
