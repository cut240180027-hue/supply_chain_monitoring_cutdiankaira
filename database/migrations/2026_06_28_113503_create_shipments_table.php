<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();

            $table->string('shipment_code')->unique();

            $table->foreignId('supplier_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('origin_country_id')
                ->constrained('countries')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('destination_country_id')
                ->constrained('countries')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('origin_port_id')
                ->constrained('ports')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('destination_port_id')
                ->constrained('ports')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('vessel_name');
            $table->date('departure_date');
            $table->date('estimated_arrival');
            $table->string('status');
            $table->string('risk_level');

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};