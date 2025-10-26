<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // User subscriptions table for payment system
        if (!Schema::hasTable('user_subscriptions')) {
            Schema::create('user_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
                $table->bigInteger('product_id');
                $table->foreign('product_id')->references('id')->on('service')->onDelete('cascade');
                $table->bigInteger('credit_card_id')->nullable();
                $table->foreign('credit_card_id')->references('id')->on('credit_cards')->onDelete('set null');
                $table->string('status')->default('active');
                $table->decimal('price', 10, 2)->default(0);
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->date('cancel_at')->nullable();
                $table->boolean('is_cancelled')->default(false);
                $table->boolean('can_reactivate')->default(true);
                $table->json('settings')->nullable();
                $table->string('payment_method')->nullable();
                $table->string('external_id')->nullable();
                $table->timestamps();
                
                $table->index('user_id');
                $table->index('status');
                $table->index('is_cancelled');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
