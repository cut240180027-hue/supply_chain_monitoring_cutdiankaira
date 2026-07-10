<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WeatherLog;

class WeatherLogSeeder extends Seeder
{
    public function run(): void
    {
        WeatherLog::insert([
            [
                'shipment_id' => 1,
                'temperature' => 30,
                'rainfall' => 12,
                'wind_speed' => 18,
                'storm_risk' => 20,
                'weather_status' => 'Cloudy',
                'recorded_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipment_id' => 2,
                'temperature' => 28,
                'rainfall' => 40,
                'wind_speed' => 35,
                'storm_risk' => 75,
                'weather_status' => 'Storm',
                'recorded_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipment_id' => 3,
                'temperature' => 26,
                'rainfall' => 5,
                'wind_speed' => 12,
                'storm_risk' => 10,
                'weather_status' => 'Sunny',
                'recorded_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}