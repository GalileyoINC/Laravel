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
        Schema::create('influencer_page', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('id_subscription')->index('fk-influencer_page-id_subscription');
            $table->string('title');
            $table->string('alias')->unique('alias');
            $table->text('description');
            $table->string('image', 63)->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('influencer_page');
    }
};
