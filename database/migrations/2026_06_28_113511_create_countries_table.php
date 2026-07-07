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
    Schema::create('countries', function (Blueprint $table) {
        $table->id();

        $table->string('country_code')->unique();
        $table->string('country_name');

        $table->string('currency');
        $table->string('currency_code');

        $table->string('capital')->nullable();

        $table->string('region')->nullable();

        $table->string('subregion')->nullable();

        $table->string('timezone')->nullable();

        $table->string('language')->nullable();

        $table->double('latitude')->nullable();

        $table->double('longitude')->nullable();

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
