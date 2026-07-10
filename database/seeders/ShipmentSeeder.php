<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shipment;

class ShipmentSeeder extends Seeder
{
    public function run(): void
    {
        Shipment::insert([
            [
                'id' => 1,
                'shipment_code' => 'SH001',
                'supplier_id' => 1,
                'origin_country_id' => 1,
                'destination_country_id' => 2,
                'origin_port_id' => 1,
                'destination_port_id' => 2,
                'vessel_name' => 'MSC Aurora',
                'departure_date' => '2026-07-01',
                'estimated_arrival' => '2026-07-15',
                'status' => 'On Shipping',
                'risk_level' => 'Medium',
                'latitude' => 31.2304,
                'longitude' => 121.4737,
                'description' => 'Electronic Products',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'shipment_code' => 'SH002',
                'supplier_id' => 2,
                'origin_country_id' => 3,
                'destination_country_id' => 2,
                'origin_port_id' => 3,
                'destination_port_id' => 4,
                'vessel_name' => 'Ever Green',
                'departure_date' => '2026-07-03',
                'estimated_arrival' => '2026-07-18',
                'status' => 'Delayed',
                'risk_level' => 'High',
                'latitude' => 35.6762,
                'longitude' => 139.6503,
                'description' => 'Automotive Parts',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'shipment_code' => 'SH003',
                'supplier_id' => 3,
                'origin_country_id' => 4,
                'destination_country_id' => 2,
                'origin_port_id' => 5,
                'destination_port_id' => 6,
                'vessel_name' => 'Ocean Queen',
                'departure_date' => '2026-07-05',
                'estimated_arrival' => '2026-07-19',
                'status' => 'Pending',
                'risk_level' => 'Low',
                'latitude' => 35.1796,
                'longitude' => 129.0756,
                'description' => 'Electronic Devices',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}