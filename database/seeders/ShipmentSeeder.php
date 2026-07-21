<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shipment;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use App\Models\Port;
use App\Models\Supplier;

class ShipmentSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate to allow re-seeding without duplicate errors
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Shipment::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Resolve country IDs by code
        $cn = Country::where('country_code', 'CN')->value('id');
        $id = Country::where('country_code', 'ID')->value('id');
        $jp = Country::where('country_code', 'JP')->value('id');
        $kr = Country::where('country_code', 'KR')->value('id');
        $sg = Country::where('country_code', 'SG')->value('id');
        $us = Country::where('country_code', 'US')->value('id');

        // Resolve port IDs by name (first match)
        $getPort = fn(string $name) => Port::where('port_name', 'like', "%{$name}%")->value('id');

        $shanghaiPort   = $getPort('Shanghai') ?? 1;
        $priokPort      = $getPort('Priok')    ?? 2;
        $tokyoPort      = $getPort('Tokyo')    ?? 3;
        $perakPort      = $getPort('Perak')    ?? 4;
        $busanPort      = $getPort('Busan')    ?? 5;
        $singaporePort  = $getPort('Singapore') ?? 6;

        // Resolve a supplier ID
        $sup1 = Supplier::value('id') ?? 1;
        $sup2 = Supplier::skip(1)->value('id') ?? $sup1;
        $sup3 = Supplier::skip(2)->value('id') ?? $sup1;

        $shipments = [];

        if ($cn && $id) {
            $shipments[] = [
                'shipment_code'          => 'SH001',
                'supplier_id'            => $sup1,
                'origin_country_id'      => $cn,
                'destination_country_id' => $id,
                'origin_port_id'         => $shanghaiPort,
                'destination_port_id'    => $priokPort,
                'vessel_name'            => 'MSC Aurora',
                'departure_date'         => '2026-07-01',
                'estimated_arrival'      => '2026-07-15',
                'status'                 => 'On Shipping',
                'risk_level'             => 'Medium',
                'latitude'               => 31.2304,
                'longitude'              => 121.4737,
                'description'            => 'Electronic Products',
                'created_at'             => now(),
                'updated_at'             => now(),
            ];
        }

        if ($jp && $id) {
            $shipments[] = [
                'shipment_code'          => 'SH002',
                'supplier_id'            => $sup2,
                'origin_country_id'      => $jp,
                'destination_country_id' => $id,
                'origin_port_id'         => $tokyoPort,
                'destination_port_id'    => $perakPort,
                'vessel_name'            => 'Ever Green',
                'departure_date'         => '2026-07-03',
                'estimated_arrival'      => '2026-07-18',
                'status'                 => 'Delayed',
                'risk_level'             => 'High',
                'latitude'               => 35.6762,
                'longitude'              => 139.6503,
                'description'            => 'Automotive Parts',
                'created_at'             => now(),
                'updated_at'             => now(),
            ];
        }

        if ($kr && $id) {
            $shipments[] = [
                'shipment_code'          => 'SH003',
                'supplier_id'            => $sup3,
                'origin_country_id'      => $kr,
                'destination_country_id' => $id,
                'origin_port_id'         => $busanPort,
                'destination_port_id'    => $priokPort,
                'vessel_name'            => 'Ocean Queen',
                'departure_date'         => '2026-07-05',
                'estimated_arrival'      => '2026-07-19',
                'status'                 => 'Pending',
                'risk_level'             => 'Low',
                'latitude'               => 35.1796,
                'longitude'              => 129.0756,
                'description'            => 'Electronic Devices',
                'created_at'             => now(),
                'updated_at'             => now(),
            ];
        }

        if ($us && $sg) {
            $shipments[] = [
                'shipment_code'          => 'SH004',
                'supplier_id'            => $sup1,
                'origin_country_id'      => $us,
                'destination_country_id' => $sg,
                'origin_port_id'         => $getPort('Los Angeles') ?? $shanghaiPort,
                'destination_port_id'    => $singaporePort,
                'vessel_name'            => 'Pacific Voyager',
                'departure_date'         => '2026-07-08',
                'estimated_arrival'      => '2026-07-25',
                'status'                 => 'On Shipping',
                'risk_level'             => 'Low',
                'latitude'               => 33.7395,
                'longitude'              => -118.2745,
                'description'            => 'Chemical Raw Materials',
                'created_at'             => now(),
                'updated_at'             => now(),
            ];
        }

        if (!empty($shipments)) {
            Shipment::insert($shipments);
        }
    }
}