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
        Schema::table('address', function (Blueprint $table) {
            $table->foreign(['id_invoice'], 'fk-address-id_invoice')->references(['id'])->on('invoice')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['id_user'], 'fk-address-id_user')->references(['id'])->on('user')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('address', function (Blueprint $table) {
            $table->dropForeign('fk-address-id_invoice');
            $table->dropForeign('fk-address-id_user');
        });
    }
};
