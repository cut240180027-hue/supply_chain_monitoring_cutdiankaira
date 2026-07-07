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
    Schema::create('risk_scores', function (Blueprint $table) {

        $table->id();

        $table->foreignId('shipment_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->integer('weather_score');

        $table->integer('currency_score');

        $table->integer('port_score');

        $table->integer('geopolitical_score');

        $table->integer('economic_score');

        $table->integer('total_score');

        $table->enum('risk_level',[
            'Low',
            'Medium',
            'High'
        ]);

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_scores');
    }
};
