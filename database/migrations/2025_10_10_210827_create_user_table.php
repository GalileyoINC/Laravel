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
        Schema::create('user', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('email')->nullable()->unique('email');
            $table->string('auth_key', 32);
            $table->string('password_hash')->nullable();
            $table->string('password_reset_token')->nullable()->unique('password_reset_token');
            $table->tinyInteger('role')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('phone_profile', 63)->nullable();
            $table->string('country', 3)->nullable();
            $table->string('state', 3)->nullable();
            $table->boolean('is_influencer')->nullable()->default(false);
            $table->string('zip', 10)->nullable();
            $table->string('anet_customer_profile_id')->nullable();
            $table->string('anet_customer_shipping_address_id')->nullable();
            $table->integer('bonus_point')->default(0);
            $table->string('image', 63)->nullable();
            $table->string('timezone', 63)->nullable();
            $table->string('verification_token')->nullable();
            $table->boolean('is_valid_email')->default(false);
            $table->text('refer_custom')->nullable();
            $table->dateTime('pay_at')->nullable();
            $table->bigInteger('refer_type')->nullable();
            $table->string('name_as_referral')->nullable();
            $table->text('affiliate_token')->nullable();
            $table->boolean('is_receive_subscribe')->default(true);
            $table->boolean('is_receive_list')->default(true);
            $table->tinyInteger('pay_day')->nullable();
            $table->boolean('is_test')->default(false);
            $table->string('admin_token')->nullable();
            $table->boolean('is_assistant')->default(false);
            $table->date('cancel_at')->nullable();
            $table->tinyInteger('tour')->nullable();
            $table->bigInteger('id_sps')->nullable();
            $table->json('sps_data')->nullable();
            $table->boolean('is_sps_active')->nullable()->default(false);
            $table->dateTime('sps_terminated_at')->nullable();
            $table->tinyInteger('general_visibility')->nullable()->default(0);
            $table->tinyInteger('phone_visibility')->nullable()->default(1);
            $table->tinyInteger('address_visibility')->nullable()->default(0);
            $table->decimal('last_price', 10)->nullable();
            $table->float('credit')->nullable()->default(0);
            $table->boolean('is_bad_email')->nullable();
            $table->string('promocode', 25)->nullable();
            $table->tinyInteger('test_message_qnt')->nullable()->default(0);
            $table->dateTime('test_message_at')->nullable();
            $table->string('city')->nullable();
            $table->bigInteger('id_inviter')->nullable()->index('fk-user-id_inviter');
            $table->tinyInteger('source')->nullable();
            $table->text('about')->nullable();
            $table->string('header_image', 63)->nullable();
            $table->string('name_search')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
