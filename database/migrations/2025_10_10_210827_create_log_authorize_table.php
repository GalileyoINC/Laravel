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
        Schema::create('log_authorize', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_user')->nullable()->index('fk-log_authorize-id_user');
            $table->bigInteger('id_money_transaction')->nullable()->index('fk-log_authorize-id_money_transaction');
            $table->string('name')->nullable();
            $table->json('request')->nullable();
            $table->json('response')->nullable();
            $table->tinyInteger('status')->nullable()->default(0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_authorize');
    }
};
