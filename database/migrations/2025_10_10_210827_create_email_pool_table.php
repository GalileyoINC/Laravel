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
        Schema::create('email_pool', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('type')->default(0);
            $table->string('to')->nullable();
            $table->string('from')->nullable();
            $table->string('reply')->nullable();
            $table->string('bcc')->nullable();
            $table->string('subject')->nullable();
            $table->text('body')->nullable();
            $table->text('bodyPlain')->nullable();
            $table->dateTime('created_at')->index('idx_email_pool_created_at');
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_pool');
    }
};
