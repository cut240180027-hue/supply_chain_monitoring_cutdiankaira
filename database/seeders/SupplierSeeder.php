<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Country;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        // Resolve country IDs by country_code so this works after API-based country seeding
        $getCountry = fn(string $code) => Country::where('country_code', $code)->value('id');

        $suppliers = [
            [
                'country_id'   => $getCountry('CN'),
                'company_name' => 'Alibaba Group',
                'address'      => 'Hangzhou, China',
                'email'        => 'info@alibaba.com',
                'phone'        => '+86 571 85022088',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'country_id'   => $getCountry('JP'),
                'company_name' => 'Toyota Motor',
                'address'      => 'Toyota City, Japan',
                'email'        => 'info@toyota.com',
                'phone'        => '+81 565 28 2121',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'country_id'   => $getCountry('KR'),
                'company_name' => 'Samsung Electronics',
                'address'      => 'Seoul, South Korea',
                'email'        => 'info@samsung.com',
                'phone'        => '+82 2 2255 0114',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'country_id'   => $getCountry('US'),
                'company_name' => 'Apple Inc.',
                'address'      => 'Cupertino, California, USA',
                'email'        => 'info@apple.com',
                'phone'        => '+1 408 996 1010',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'country_id'   => $getCountry('DE'),
                'company_name' => 'BASF SE',
                'address'      => 'Ludwigshafen, Germany',
                'email'        => 'info@basf.com',
                'phone'        => '+49 621 60 0',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'country_id'   => $getCountry('ID'),
                'company_name' => 'Salim Group',
                'address'      => 'Jakarta, Indonesia',
                'email'        => 'info@salimgroup.co.id',
                'phone'        => '+62 21 5795 3333',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ];

        // Filter out suppliers where country_id could not be resolved
        $suppliers = array_filter($suppliers, fn($s) => !is_null($s['country_id']));

        Supplier::insert(array_values($suppliers));
    }
}