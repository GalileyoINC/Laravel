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
        Schema::create('invoice_promocode', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_promo');
            $table->bigInteger('id_invoice')->index('fk_invoice_promocode_id_invoice');
            $table->dateTime('created_at')->nullable()->useCurrent();

            $table->unique(['id_promo', 'id_invoice'], 'uk_invoice_promocode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_promocode');
    }
};
