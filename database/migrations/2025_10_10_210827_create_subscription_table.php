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
        Schema::create('subscription', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_subscription_category')->index('fk-subscription-id_subscription_category');
            $table->string('name');
            $table->string('title')->nullable();
            $table->tinyInteger('position_no')->nullable();
            $table->string('description')->nullable();
            $table->string('rule', 100)->nullable();
            $table->tinyInteger('alert_type')->nullable()->default(1);
            $table->tinyInteger('is_active')->nullable()->default(0);
            $table->tinyInteger('is_hidden')->default(0);
            $table->decimal('percent', 10)->default(0);
            $table->dateTime('sended_at')->nullable();
            $table->json('params')->nullable();
            $table->boolean('is_custom')->nullable()->default(false);
            $table->bigInteger('id_influencer')->nullable()->index('fk-subscription-id_influencer');
            $table->decimal('price', 10)->default(0);
            $table->integer('bonus_point')->default(0);
            $table->string('token')->nullable();
            $table->string('ipfs_id')->nullable();
            $table->boolean('is_public')->default(false);
            $table->boolean('is_fake')->default(false);
            $table->tinyInteger('type')->nullable()->default(0);
            $table->boolean('show_reactions')->default(false);
            $table->boolean('show_comments')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription');
    }
};
