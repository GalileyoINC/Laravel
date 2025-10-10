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
        Schema::create('credit_card', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_user')->nullable()->index('fk_credit_card_id_user');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('num')->nullable();
            $table->string('cvv')->nullable();
            $table->string('type', 63)->nullable();
            $table->integer('expiration_year')->nullable();
            $table->tinyInteger('expiration_month')->nullable();
            $table->boolean('is_active')->default(true);
            $table->tinyInteger('is_preferred')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->string('anet_customer_payment_profile_id')->nullable();
            $table->tinyInteger('anet_profile_deleted')->default(0);
            $table->text('phone')->nullable();
            $table->text('zip')->nullable();
            $table->boolean('is_agree_to_receive')->default(false);
            $table->json('billing_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_card');
    }
};
