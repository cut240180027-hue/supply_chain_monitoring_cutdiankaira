<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsLog;

class NewsLogSeeder extends Seeder
{
    public function run(): void
    {
        NewsLog::insert([
            [
                'country_id' => 1,
                'shipment_id' => 1,
                'title' => 'Heavy congestion at Shanghai Port',
                'source' => 'Reuters',
                'risk_level' => 'Medium',
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'country_id' => 3,
                'shipment_id' => 2,
                'title' => 'Typhoon affects Tokyo shipping routes',
                'source' => 'NHK News',
                'risk_level' => 'High',
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'country_id' => 4,
                'shipment_id' => 3,
                'title' => 'Busan Port operates normally',
                'source' => 'Korea Times',
                'risk_level' => 'Low',
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}