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
        Schema::create('comment', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_sms_pool')->nullable()->index('idx_comment_sms_pool');
            $table->bigInteger('id_user')->nullable()->index('idx_comment_user');
            $table->text('message');
            $table->dateTime('created_at')->index('idx_comment_created_at');
            $table->dateTime('updated_at')->nullable();
            $table->bigInteger('id_parent')->nullable()->index('idx_comment_parent');
            $table->boolean('is_deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment');
    }
};
