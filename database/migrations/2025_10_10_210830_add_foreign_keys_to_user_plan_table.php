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
        Schema::table('user_plan', function (Blueprint $table) {
            $table->foreign(['id_invoice_line'], 'fk-user_plan-id_invoice_line')->references(['id'])->on('invoice_line')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_service'], 'fk-user_plan-id_service')->references(['id'])->on('service')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_user'], 'fk-user_plan-id_user')->references(['id'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_plan', function (Blueprint $table) {
            $table->dropForeign('fk-user_plan-id_invoice_line');
            $table->dropForeign('fk-user_plan-id_service');
            $table->dropForeign('fk-user_plan-id_user');
        });
    }
};
