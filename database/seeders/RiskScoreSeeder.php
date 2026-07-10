<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiskScore;

class RiskScoreSeeder extends Seeder
{
    public function run(): void
    {
        RiskScore::insert([
            [
                'shipment_id' => 1,
                'weather_score' => 20,
                'currency_score' => 15,
                'port_score' => 18,
                'geopolitical_score' => 12,
                'economic_score' => 16,
                'total_score' => 81,
                'risk_level' => 'Medium',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipment_id' => 2,
                'weather_score' => 35,
                'currency_score' => 20,
                'port_score' => 28,
                'geopolitical_score' => 22,
                'economic_score' => 18,
                'total_score' => 123,
                'risk_level' => 'High',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipment_id' => 3,
                'weather_score' => 10,
                'currency_score' => 10,
                'port_score' => 12,
                'geopolitical_score' => 8,
                'economic_score' => 9,
                'total_score' => 49,
                'risk_level' => 'Low',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}