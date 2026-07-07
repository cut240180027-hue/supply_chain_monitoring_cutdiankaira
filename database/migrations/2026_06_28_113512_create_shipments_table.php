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
    Schema::create('shipments', function (Blueprint $table) {

        $table->id();

        $table->string('shipment_code')->unique();

        $table->foreignId('supplier_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->foreignId('origin_country_id')
              ->constrained('countries');

        $table->foreignId('destination_country_id')
              ->constrained('countries');

        $table->foreignId('origin_port_id')
              ->constrained('ports');

        $table->foreignId('destination_port_id')
              ->constrained('ports');

        $table->date('departure_date');

        $table->date('eta');

        $table->date('arrival_date')->nullable();

        $table->enum('status',[
            'Pending',
            'On Shipping',
            'Delayed',
            'Arrived'
        ]);

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
