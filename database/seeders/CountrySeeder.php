<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        Country::insert([
            [
                'id' => 1,
                'country_code' => 'CN',
                'country_name' => 'China',
                'currency' => 'Yuan',
                'currency_code' => 'CNY',
                'capital' => 'Beijing',
                'region' => 'Asia',
                'subregion' => 'East Asia',
                'timezone' => 'UTC+8',
                'language' => 'Chinese',
                'latitude' => 35.8617,
                'longitude' => 104.1954,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'country_code' => 'ID',
                'country_name' => 'Indonesia',
                'currency' => 'Rupiah',
                'currency_code' => 'IDR',
                'capital' => 'Jakarta',
                'region' => 'Asia',
                'subregion' => 'Southeast Asia',
                'timezone' => 'UTC+7',
                'language' => 'Indonesian',
                'latitude' => -0.7893,
                'longitude' => 113.9213,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'country_code' => 'JP',
                'country_name' => 'Japan',
                'currency' => 'Yen',
                'currency_code' => 'JPY',
                'capital' => 'Tokyo',
                'region' => 'Asia',
                'subregion' => 'East Asia',
                'timezone' => 'UTC+9',
                'language' => 'Japanese',
                'latitude' => 36.2048,
                'longitude' => 138.2529,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'country_code' => 'KR',
                'country_name' => 'South Korea',
                'currency' => 'Won',
                'currency_code' => 'KRW',
                'capital' => 'Seoul',
                'region' => 'Asia',
                'subregion' => 'East Asia',
                'timezone' => 'UTC+9',
                'language' => 'Korean',
                'latitude' => 35.9078,
                'longitude' => 127.7669,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}