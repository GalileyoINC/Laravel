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
        Schema::create('sms_pool', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_user')->nullable();
            $table->bigInteger('id_staff')->nullable()->index('fk-sms_pool-id_staff');
            $table->bigInteger('id_subscription')->nullable()->index('idx_sms_pool_subscription');
            $table->bigInteger('id_follower_list')->nullable();
            $table->tinyInteger('purpose')->nullable()->index('idx_sms_pool_purpose');
            $table->tinyInteger('status')->default(1);
            $table->text('body');
            $table->dateTime('created_at')->index('idx_sms_pool_created_at');
            $table->dateTime('updated_at')->nullable();
            $table->string('sms_provider', 63)->nullable();
            $table->bigInteger('id_assistant')->nullable()->index('fk-sms_pool-id_assistant');
            $table->string('short_body', 160)->nullable();
            $table->string('url', 512)->nullable();
            $table->boolean('is_ban')->default(false);

            $table->index(['id_user', 'status'], 'idx_sms_pool_user_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_pool');
    }
};
