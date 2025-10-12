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
        Schema::table('email_pool_attachment', function (Blueprint $table) {
            $table->foreign(['id_email_pool'], 'FK_email_pool_attachment_email_pool_id')->references(['id'])->on('email_pool')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_pool_attachment', function (Blueprint $table) {
            $table->dropForeign('FK_email_pool_attachment_email_pool_id');
        });
    }
};
