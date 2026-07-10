<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExchangeRate;

class ExchangeRateSeeder extends Seeder
{
    public function run(): void
    {
        ExchangeRate::insert([
            [
                'shipment_id' => 1,
                'currency_code' => 'CNY',
                'exchange_rate' => 2250,
                'recorded_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipment_id' => 2,
                'currency_code' => 'JPY',
                'exchange_rate' => 108,
                'recorded_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipment_id' => 3,
                'currency_code' => 'KRW',
                'exchange_rate' => 11.5,
                'recorded_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}