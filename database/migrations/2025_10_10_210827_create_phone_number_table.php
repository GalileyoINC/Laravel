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
        Schema::create('phone_number', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_user')->index('fk-phone_number-id_user');
            $table->bigInteger('id_provider')->nullable()->index('fk-phone_number-id_provider');
            $table->tinyInteger('type')->nullable()->default(1);
            $table->boolean('is_satellite')->nullable()->default(false);
            $table->string('number');
            $table->boolean('is_active')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_send')->default(false);
            $table->boolean('is_emergency_only')->default(false);
            $table->boolean('is_valid')->default(false);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->string('twilio_type', 63)->nullable();
            $table->json('twilio_type_raw')->nullable();
            $table->string('numverify_type', 63)->nullable();
            $table->json('numverify_raw')->nullable();
            $table->json('bivy_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone_number');
    }
};
