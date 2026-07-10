<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EconomicIndicator;

class EconomicIndicatorSeeder extends Seeder
{
    public function run(): void
    {
        EconomicIndicator::insert([
            [
                'country_id' => 1,
                'gdp' => 17963.2,
                'inflation' => 2.1,
                'export_value' => 3593.6,
                'import_value' => 2715.4,
                'population' => 1412000000,
                'year' => 2026,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'country_id' => 2,
                'gdp' => 1492.6,
                'inflation' => 3.2,
                'export_value' => 291.9,
                'import_value' => 245.4,
                'population' => 281000000,
                'year' => 2026,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'country_id' => 3,
                'gdp' => 4231.1,
                'inflation' => 1.8,
                'export_value' => 746.8,
                'import_value' => 720.1,
                'population' => 123000000,
                'year' => 2026,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'country_id' => 4,
                'gdp' => 1810.4,
                'inflation' => 2.6,
                'export_value' => 683.5,
                'import_value' => 642.0,
                'population' => 51700000,
                'year' => 2026,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}