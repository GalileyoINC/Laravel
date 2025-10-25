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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_user');
            $table->bigInteger('id_product');
            $table->integer('quantity');
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method')->default('credit_card');
            $table->string('status')->default('pending');
            $table->boolean('is_paid')->default(false);
            $table->text('notes')->nullable();
            $table->json('product_details')->nullable();
            $table->bigInteger('id_credit_card')->nullable();
            $table->string('payment_reference')->nullable();
            $table->json('payment_details')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('user');
            $table->foreign('id_product')->references('id')->on('service');
            
            // Only add credit card foreign key if credit_cards table exists
            if (Schema::hasTable('credit_cards')) {
                $table->foreign('id_credit_card')->references('id')->on('credit_cards');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
