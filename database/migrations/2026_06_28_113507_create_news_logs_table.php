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
    Schema::create('news_logs', function (Blueprint $table) {

        $table->id();

        $table->foreignId('country_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->foreignId('shipment_id')
              ->nullable()
              ->constrained()
              ->nullOnDelete();

        $table->string('title');

        $table->string('source');

        $table->enum('risk_level',[
            'Low',
            'Medium',
            'High'
        ]);

        $table->timestamp('published_at');

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_logs');
    }
};
