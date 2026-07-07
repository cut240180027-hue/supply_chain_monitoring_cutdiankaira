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
    Schema::create('exchange_rates', function (Blueprint $table) {

        $table->id();

        $table->foreignId('shipment_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->string('currency_code');

        $table->double('exchange_rate');

        $table->timestamp('recorded_at');

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
