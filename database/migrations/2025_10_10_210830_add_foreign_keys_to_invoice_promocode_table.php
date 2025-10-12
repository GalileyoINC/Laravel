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
        Schema::table('invoice_promocode', function (Blueprint $table) {
            $table->foreign(['id_invoice'], 'FK_invoice_promocode_id_invoice')->references(['id'])->on('invoice')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_promo'], 'FK_invoice_promocode_id_promo')->references(['id'])->on('promocode')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_promocode', function (Blueprint $table) {
            $table->dropForeign('FK_invoice_promocode_id_invoice');
            $table->dropForeign('FK_invoice_promocode_id_promo');
        });
    }
};
