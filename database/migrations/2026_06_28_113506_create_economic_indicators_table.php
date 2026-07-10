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
    Schema::create('economic_indicators', function (Blueprint $table) {

        $table->id();

        $table->foreignId('country_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->double('gdp')->nullable();

        $table->double('inflation')->nullable();

        $table->double('export_value')->nullable();

        $table->double('import_value')->nullable();

        $table->bigInteger('population')->nullable();

        $table->year('year');

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('economic_indicators');
    }
};
