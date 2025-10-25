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
        Schema::create('credit_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('num'); // Masked card number
            $table->string('phone')->nullable();
            $table->string('zip')->nullable();
            $table->string('cvv')->nullable(); // Encrypted CVV
            $table->string('type')->nullable(); // Visa, MasterCard, etc.
            $table->integer('expiration_year');
            $table->integer('expiration_month');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_preferred')->default(false);
            $table->boolean('is_agree_to_receive')->default(false);
            
            // Authorize.net integration fields
            $table->string('anet_customer_payment_profile_id')->nullable();
            $table->boolean('anet_profile_deleted')->default(false);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'is_active']);
            $table->index(['user_id', 'is_preferred']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_cards');
    }
};