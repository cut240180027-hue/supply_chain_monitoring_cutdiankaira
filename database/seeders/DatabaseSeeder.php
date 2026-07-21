<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CountrySeeder::class,
            PortSeeder::class,
            SupplierSeeder::class,
            ShipmentSeeder::class,
            WeatherLogSeeder::class,
            ExchangeRateSeeder::class,
            EconomicIndicatorSeeder::class,
            NewsLogSeeder::class,
            RiskScoreSeeder::class,
            LexiconSeeder::class,
        ]);
    }
}