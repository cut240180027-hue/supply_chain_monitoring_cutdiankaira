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
    Schema::create('weather_logs', function (Blueprint $table) {

        $table->id();

        $table->foreignId('shipment_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->double('temperature')->nullable();

        $table->double('rainfall')->nullable();

        $table->double('wind_speed')->nullable();

        $table->double('storm_risk')->nullable();

        $table->string('weather_status')->nullable();

        $table->timestamp('recorded_at');

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_logs');
    }
};
