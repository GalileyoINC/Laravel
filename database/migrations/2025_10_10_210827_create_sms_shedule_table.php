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
        Schema::create('sms_shedule', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_user')->nullable()->index('fk-sms_shedule-id_user');
            $table->bigInteger('id_staff')->nullable()->index('fk-sms_shedule-id_staff');
            $table->bigInteger('id_subscription')->nullable()->index('fk-sms_shedule-id_subscription');
            $table->bigInteger('id_follower_list')->nullable()->index('fk-sms_shedule-id_follower_list');
            $table->bigInteger('id_sms_pool')->nullable()->index('fk-sms_shedule-id_sms_pool');
            $table->tinyInteger('purpose')->nullable();
            $table->tinyInteger('status')->nullable()->default(0);
            $table->text('body');
            $table->dateTime('sended_at');
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->bigInteger('id_assistant')->nullable()->index('fk-sms_shedule-id_assistant');
            $table->string('short_body', 160)->nullable();
            $table->string('url', 512)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_shedule');
    }
};
