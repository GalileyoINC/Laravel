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
        Schema::table('provider_twilio_carrier', function (Blueprint $table) {
            $table->foreign(['id_provider'], 'fk-provider_twilio_carrier-id_provider')->references(['id'])->on('provider')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_twilio_carrier'], 'fk-provider_twilio_carrier-id_twilio_carrier')->references(['id'])->on('twilio_carrier')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provider_twilio_carrier', function (Blueprint $table) {
            $table->dropForeign('fk-provider_twilio_carrier-id_provider');
            $table->dropForeign('fk-provider_twilio_carrier-id_twilio_carrier');
        });
    }
};
