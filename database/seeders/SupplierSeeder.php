<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::insert([
            [
                'id'=>1,
                'country_id'=>1,
                'company_name'=>'Alibaba Group',
                'address'=>'Hangzhou, China',
                'email'=>'info@alibaba.com',
                'phone'=>'+86 571 85022088',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'id'=>2,
                'country_id'=>3,
                'company_name'=>'Toyota Motor',
                'address'=>'Toyota City, Japan',
                'email'=>'info@toyota.com',
                'phone'=>'+81 565 28 2121',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'id'=>3,
                'country_id'=>4,
                'company_name'=>'Samsung Electronics',
                'address'=>'Seoul, South Korea',
                'email'=>'info@samsung.com',
                'phone'=>'+82 2 2255 0114',
                'created_at'=>now(),
                'updated_at'=>now(),
            ]
        ]);
    }
}