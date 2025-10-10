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
        Schema::create('contract_line', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_user')->nullable()->index('fk-contract_line-id_user');
            $table->bigInteger('id_service')->nullable()->index('fk-contract_line-id_service');
            $table->bigInteger('id_sps_contract')->nullable()->index('fk-contract_line-id_sps_contract');
            $table->tinyInteger('type')->nullable();
            $table->tinyInteger('pay_interval')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('period_price', 10)->nullable();
            $table->json('custom_data')->nullable();
            $table->date('terminated_at')->nullable();
            $table->date('begin_at')->nullable();
            $table->date('end_at')->nullable();
            $table->boolean('is_sps_line')->default(false);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->string('title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_line');
    }
};
