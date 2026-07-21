<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\EconomicIndicator;

class EconomicIndicatorSeeder extends Seeder
{
    /**
     * Data ekonomi riil per negara (GDP dalam miliar USD, tahun 2026).
     * Sumber: World Bank, IMF estimates.
     */
    protected array $knownData = [
        'CN' => ['gdp'=>17963.2, 'inflation'=>2.1,  'export_value'=>3593.6, 'import_value'=>2715.4, 'population'=>1412000000],
        'ID' => ['gdp'=>1492.6,  'inflation'=>3.2,  'export_value'=>291.9,  'import_value'=>245.4,  'population'=>281000000 ],
        'JP' => ['gdp'=>4231.1,  'inflation'=>1.8,  'export_value'=>746.8,  'import_value'=>720.1,  'population'=>123000000 ],
        'KR' => ['gdp'=>1810.4,  'inflation'=>2.6,  'export_value'=>683.5,  'import_value'=>642.0,  'population'=>51700000  ],
        'IN' => ['gdp'=>3737.8,  'inflation'=>5.4,  'export_value'=>447.4,  'import_value'=>714.1,  'population'=>1417000000],
        'MY' => ['gdp'=>430.6,   'inflation'=>2.8,  'export_value'=>299.5,  'import_value'=>241.2,  'population'=>33600000  ],
        'SG' => ['gdp'=>501.4,   'inflation'=>1.5,  'export_value'=>515.5,  'import_value'=>486.3,  'population'=>5900000   ],
        'TH' => ['gdp'=>543.6,   'inflation'=>1.2,  'export_value'=>285.9,  'import_value'=>252.4,  'population'=>72000000  ],
        'VN' => ['gdp'=>449.1,   'inflation'=>4.1,  'export_value'=>371.9,  'import_value'=>360.2,  'population'=>97900000  ],
        'PH' => ['gdp'=>440.9,   'inflation'=>4.9,  'export_value'=>64.3,   'import_value'=>117.5,  'population'=>115000000 ],
        'PK' => ['gdp'=>347.0,   'inflation'=>20.5, 'export_value'=>31.1,   'import_value'=>55.5,   'population'=>231000000 ],
        'BD' => ['gdp'=>460.2,   'inflation'=>9.5,  'export_value'=>55.6,   'import_value'=>85.3,   'population'=>170000000 ],
        'SA' => ['gdp'=>1061.9,  'inflation'=>2.5,  'export_value'=>359.9,  'import_value'=>157.9,  'population'=>35600000  ],
        'AE' => ['gdp'=>507.5,   'inflation'=>2.3,  'export_value'=>339.7,  'import_value'=>280.9,  'population'=>9900000   ],
        'TR' => ['gdp'=>905.8,   'inflation'=>51.7, 'export_value'=>254.2,  'import_value'=>341.8,  'population'=>85000000  ],
        'TW' => ['gdp'=>790.7,   'inflation'=>2.0,  'export_value'=>479.0,  'import_value'=>385.8,  'population'=>23400000  ],
        'HK' => ['gdp'=>369.5,   'inflation'=>1.9,  'export_value'=>528.7,  'import_value'=>534.7,  'population'=>7500000   ],
        'IQ' => ['gdp'=>264.2,   'inflation'=>5.5,  'export_value'=>102.1,  'import_value'=>74.6,   'population'=>42300000  ],
        'IR' => ['gdp'=>368.9,   'inflation'=>40.0, 'export_value'=>51.5,   'import_value'=>60.3,   'population'=>86800000  ],
        'IL' => ['gdp'=>539.2,   'inflation'=>3.5,  'export_value'=>143.5,  'import_value'=>116.0,  'population'=>9800000   ],
        'DE' => ['gdp'=>4429.8,  'inflation'=>2.2,  'export_value'=>1750.6, 'import_value'=>1512.3, 'population'=>84300000  ],
        'GB' => ['gdp'=>3070.6,  'inflation'=>2.6,  'export_value'=>469.8,  'import_value'=>692.0,  'population'=>67200000  ],
        'FR' => ['gdp'=>2923.5,  'inflation'=>1.8,  'export_value'=>610.4,  'import_value'=>721.5,  'population'=>68400000  ],
        'NL' => ['gdp'=>1120.7,  'inflation'=>2.4,  'export_value'=>952.3,  'import_value'=>868.5,  'population'=>17900000  ],
        'IT' => ['gdp'=>2169.7,  'inflation'=>1.5,  'export_value'=>666.3,  'import_value'=>611.9,  'population'=>59200000  ],
        'ES' => ['gdp'=>1581.3,  'inflation'=>2.3,  'export_value'=>423.0,  'import_value'=>450.5,  'population'=>47400000  ],
        'RU' => ['gdp'=>1862.3,  'inflation'=>8.4,  'export_value'=>428.3,  'import_value'=>260.0,  'population'=>145000000 ],
        'PL' => ['gdp'=>780.7,   'inflation'=>4.1,  'export_value'=>365.3,  'import_value'=>345.9,  'population'=>37700000  ],
        'BE' => ['gdp'=>613.8,   'inflation'=>2.6,  'export_value'=>529.5,  'import_value'=>516.1,  'population'=>11600000  ],
        'SE' => ['gdp'=>618.5,   'inflation'=>1.7,  'export_value'=>219.0,  'import_value'=>204.5,  'population'=>10500000  ],
        'CH' => ['gdp'=>905.7,   'inflation'=>1.3,  'export_value'=>432.5,  'import_value'=>376.4,  'population'=>8800000   ],
        'NO' => ['gdp'=>546.0,   'inflation'=>3.5,  'export_value'=>229.6,  'import_value'=>113.4,  'population'=>5500000   ],
        'DK' => ['gdp'=>416.2,   'inflation'=>2.2,  'export_value'=>175.9,  'import_value'=>148.0,  'population'=>5900000   ],
        'AT' => ['gdp'=>517.0,   'inflation'=>3.0,  'export_value'=>241.7,  'import_value'=>219.5,  'population'=>9100000   ],
        'FI' => ['gdp'=>306.6,   'inflation'=>1.8,  'export_value'=>103.7,  'import_value'=>99.3,   'population'=>5600000   ],
        'GR' => ['gdp'=>239.3,   'inflation'=>3.2,  'export_value'=>74.1,   'import_value'=>96.4,   'population'=>10500000  ],
        'PT' => ['gdp'=>277.1,   'inflation'=>2.0,  'export_value'=>103.2,  'import_value'=>109.7,  'population'=>10300000  ],
        'US' => ['gdp'=>27360.9, 'inflation'=>3.0,  'export_value'=>3056.1, 'import_value'=>3825.2, 'population'=>334000000 ],
        'CA' => ['gdp'=>2117.8,  'inflation'=>2.9,  'export_value'=>570.2,  'import_value'=>556.9,  'population'=>40000000  ],
        'MX' => ['gdp'=>1414.2,  'inflation'=>4.5,  'export_value'=>578.1,  'import_value'=>554.6,  'population'=>130000000 ],
        'BR' => ['gdp'=>2173.6,  'inflation'=>4.6,  'export_value'=>339.7,  'import_value'=>260.3,  'population'=>215000000 ],
        'AR' => ['gdp'=>631.2,   'inflation'=>143.7,'export_value'=>79.4,   'import_value'=>72.1,   'population'=>46000000  ],
        'CL' => ['gdp'=>317.2,   'inflation'=>4.7,  'export_value'=>103.5,  'import_value'=>82.3,   'population'=>19600000  ],
        'CO' => ['gdp'=>363.9,   'inflation'=>7.2,  'export_value'=>52.1,   'import_value'=>64.8,   'population'=>51900000  ],
        'PE' => ['gdp'=>253.4,   'inflation'=>3.4,  'export_value'=>62.7,   'import_value'=>56.8,   'population'=>33400000  ],
        'VE' => ['gdp'=>92.0,    'inflation'=>300.0,'export_value'=>18.3,   'import_value'=>16.1,   'population'=>28000000  ],
        'ZA' => ['gdp'=>380.9,   'inflation'=>4.7,  'export_value'=>115.4,  'import_value'=>97.5,   'population'=>62000000  ],
        'NG' => ['gdp'=>477.2,   'inflation'=>28.9, 'export_value'=>44.9,   'import_value'=>23.1,   'population'=>220000000 ],
        'EG' => ['gdp'=>388.8,   'inflation'=>33.3, 'export_value'=>46.7,   'import_value'=>80.5,   'population'=>104000000 ],
        'MA' => ['gdp'=>143.8,   'inflation'=>2.7,  'export_value'=>44.5,   'import_value'=>59.9,   'population'=>37000000  ],
        'ET' => ['gdp'=>155.8,   'inflation'=>29.4, 'export_value'=>3.5,    'import_value'=>14.6,   'population'=>123000000 ],
        'TZ' => ['gdp'=>78.7,    'inflation'=>4.8,  'export_value'=>5.4,    'import_value'=>11.5,   'population'=>63000000  ],
        'KE' => ['gdp'=>113.4,   'inflation'=>7.5,  'export_value'=>7.1,    'import_value'=>19.9,   'population'=>55000000  ],
        'GH' => ['gdp'=>76.5,    'inflation'=>23.2, 'export_value'=>14.5,   'import_value'=>16.1,   'population'=>33000000  ],
        'DZ' => ['gdp'=>239.9,   'inflation'=>9.0,  'export_value'=>51.5,   'import_value'=>47.1,   'population'=>46000000  ],
        'LY' => ['gdp'=>51.3,    'inflation'=>5.5,  'export_value'=>25.0,   'import_value'=>14.1,   'population'=>7000000   ],
        'AU' => ['gdp'=>1724.7,  'inflation'=>3.4,  'export_value'=>395.0,  'import_value'=>322.1,  'population'=>26600000  ],
        'NZ' => ['gdp'=>252.2,   'inflation'=>3.0,  'export_value'=>52.1,   'import_value'=>60.8,   'population'=>5000000   ],
    ];

    public function run(): void
    {
        $this->command->info('Seeding economic indicators...');

        $countries = Country::all();
        $year = 2026;
        $count = 0;

        foreach ($countries as $country) {
            $code = strtoupper($country->country_code);

            if (isset($this->knownData[$code])) {
                // Use known real-world data
                $data = $this->knownData[$code];
            } else {
                // Auto-generate estimated data based on region for unknown countries
                $data = $this->generateEstimate($country);
            }

            EconomicIndicator::updateOrCreate(
                ['country_id' => $country->id, 'year' => $year],
                [
                    'gdp'          => $data['gdp'],
                    'inflation'    => $data['inflation'],
                    'export_value' => $data['export_value'],
                    'import_value' => $data['import_value'],
                    'population'   => $data['population'],
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]
            );

            $count++;
        }

        $this->command->info("✓ Economic indicators seeded for {$count} countries.");
    }

    /**
     * Generate estimated economic data for countries without known data.
     * Uses regional averages as estimation baseline.
     */
    protected function generateEstimate(Country $country): array
    {
        // Regional GDP/population baselines (rough estimate)
        $regionDefaults = [
            'Europe'   => ['gdp'=>80.0,   'inflation'=>3.5,  'export_value'=>30.0,  'import_value'=>28.0,  'population'=>8000000 ],
            'Asia'     => ['gdp'=>50.0,   'inflation'=>5.0,  'export_value'=>18.0,  'import_value'=>20.0,  'population'=>25000000],
            'Americas' => ['gdp'=>60.0,   'inflation'=>6.0,  'export_value'=>15.0,  'import_value'=>17.0,  'population'=>15000000],
            'Africa'   => ['gdp'=>30.0,   'inflation'=>8.0,  'export_value'=>8.0,   'import_value'=>10.0,  'population'=>20000000],
            'Oceania'  => ['gdp'=>15.0,   'inflation'=>3.0,  'export_value'=>5.0,   'import_value'=>6.0,   'population'=>1000000 ],
        ];

        $region = $country->region ?? 'Asia';
        $defaults = $regionDefaults[$region] ?? $regionDefaults['Asia'];

        // Add slight variation using country id as seed
        $seed = $country->id * 7 % 100;
        $factor = 0.7 + ($seed / 300); // 0.7 - 1.03 variation

        return [
            'gdp'          => round($defaults['gdp']          * $factor, 1),
            'inflation'    => round($defaults['inflation']     * (1 + ($seed % 10) / 100), 1),
            'export_value' => round($defaults['export_value']  * $factor, 1),
            'import_value' => round($defaults['import_value']  * $factor, 1),
            'population'   => (int)round($defaults['population'] * $factor),
        ];
    }
}