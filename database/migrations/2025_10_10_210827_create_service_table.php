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
        Schema::create('service', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->tinyInteger('type')->default(0);
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->float('price')->nullable();
            $table->integer('bonus_point')->default(0);
            $table->json('settings')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->boolean('is_active')->nullable()->default(false);
            $table->json('compensation')->nullable();
            $table->decimal('fee', 10)->nullable();
            $table->decimal('fee_annual', 10)->nullable();
            $table->decimal('termination_fee', 10)->nullable();
            $table->integer('termination_period')->nullable();
            $table->decimal('special_price', 10)->nullable();
            $table->boolean('is_special_price')->default(false);
            $table->decimal('special_fee', 10)->nullable();
            $table->boolean('is_special_fee')->default(false);
            $table->decimal('special_fee_annual', 10)->nullable();
            $table->boolean('is_special_fee_annual')->default(false);
            $table->dateTime('special_datetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service');
    }
};
