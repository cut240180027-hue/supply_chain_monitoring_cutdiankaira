<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Port;

class PortSeeder extends Seeder
{
    public function run(): void
    {
        Port::insert([
            [
                'id'=>1,
                'country_id'=>1,
                'port_name'=>'Shanghai Port',
                'latitude'=>31.2304,
                'longitude'=>121.4737,
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'id'=>2,
                'country_id'=>2,
                'port_name'=>'Tanjung Priok',
                'latitude'=>-6.1049,
                'longitude'=>106.8800,
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'id'=>3,
                'country_id'=>3,
                'port_name'=>'Tokyo Port',
                'latitude'=>35.6570,
                'longitude'=>139.7966,
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'id'=>4,
                'country_id'=>2,
                'port_name'=>'Tanjung Perak',
                'latitude'=>-7.2048,
                'longitude'=>112.7320,
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'id'=>5,
                'country_id'=>4,
                'port_name'=>'Busan Port',
                'latitude'=>35.1036,
                'longitude'=>129.0403,
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'id'=>6,
                'country_id'=>2,
                'port_name'=>'Belawan',
                'latitude'=>3.7850,
                'longitude'=>98.6830,
                'created_at'=>now(),
                'updated_at'=>now(),
            ]
        ]);
    }
}