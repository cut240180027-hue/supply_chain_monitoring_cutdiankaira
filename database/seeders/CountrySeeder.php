<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use Illuminate\Support\Facades\Http;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Fetching countries from REST Countries API...');

        try {
            $response = Http::withHeaders(['User-Agent' => 'Laravel'])
                ->timeout(30)
                ->get('https://raw.githubusercontent.com/mledoze/countries/master/countries.json');

            if (!$response->successful()) {
                $this->command->warn('API failed, falling back to hardcoded data.');
                $this->seedFallback();
                return;
            }

            $data = $response->json();

            if (!is_array($data)) {
                $this->command->warn('Invalid API response, falling back to hardcoded data.');
                $this->seedFallback();
                return;
            }

            $count = 0;

            foreach ($data as $item) {
                if (empty($item['cca2']) || empty($item['name']['common'])) {
                    continue;
                }

                $currencyCode = '-';
                $currencyName = '-';
                if (!empty($item['currencies'])) {
                    $currencyCode = array_key_first($item['currencies']);
                    $currencyName = current($item['currencies'])['name'] ?? '-';
                }

                $capital = '-';
                if (!empty($item['capital']) && is_array($item['capital'])) {
                    $capital = $item['capital'][0];
                }

                $timezone = '-';
                if (!empty($item['timezones']) && is_array($item['timezones'])) {
                    $timezone = $item['timezones'][0];
                }

                $language = '-';
                if (!empty($item['languages']) && is_array($item['languages'])) {
                    $language = array_values($item['languages'])[0];
                }

                $latitude  = isset($item['latlng'][0]) ? $item['latlng'][0] : null;
                $longitude = isset($item['latlng'][1]) ? $item['latlng'][1] : null;

                Country::updateOrCreate(
                    ['country_code' => $item['cca2']],
                    [
                        'country_name'  => $item['name']['common'],
                        'capital'       => $capital,
                        'region'        => $item['region']    ?? '-',
                        'subregion'     => $item['subregion']  ?? '-',
                        'currency'      => $currencyName,
                        'currency_code' => $currencyCode,
                        'timezone'      => $timezone,
                        'language'      => $language,
                        'latitude'      => $latitude,
                        'longitude'     => $longitude,
                    ]
                );

                $count++;
            }

            $this->command->info("✓ {$count} countries synced from API.");

        } catch (\Exception $e) {
            $this->command->warn('API request failed: ' . $e->getMessage() . '. Falling back to hardcoded data.');
            $this->seedFallback();
        }
    }

    /**
     * Fallback data jika API tidak dapat diakses.
     */
    protected function seedFallback(): void
    {
        $countries = [
            ['country_code'=>'CN','country_name'=>'China',        'currency'=>'Yuan',            'currency_code'=>'CNY','capital'=>'Beijing',     'region'=>'Asia',    'subregion'=>'East Asia',      'timezone'=>'UTC+8',   'language'=>'Chinese',    'latitude'=>35.8617,  'longitude'=>104.1954 ],
            ['country_code'=>'ID','country_name'=>'Indonesia',    'currency'=>'Rupiah',           'currency_code'=>'IDR','capital'=>'Jakarta',     'region'=>'Asia',    'subregion'=>'Southeast Asia', 'timezone'=>'UTC+7',   'language'=>'Indonesian', 'latitude'=>-0.7893,  'longitude'=>113.9213 ],
            ['country_code'=>'JP','country_name'=>'Japan',        'currency'=>'Yen',              'currency_code'=>'JPY','capital'=>'Tokyo',       'region'=>'Asia',    'subregion'=>'East Asia',      'timezone'=>'UTC+9',   'language'=>'Japanese',   'latitude'=>36.2048,  'longitude'=>138.2529 ],
            ['country_code'=>'KR','country_name'=>'South Korea',  'currency'=>'Won',              'currency_code'=>'KRW','capital'=>'Seoul',       'region'=>'Asia',    'subregion'=>'East Asia',      'timezone'=>'UTC+9',   'language'=>'Korean',     'latitude'=>35.9078,  'longitude'=>127.7669 ],
            ['country_code'=>'IN','country_name'=>'India',        'currency'=>'Indian Rupee',     'currency_code'=>'INR','capital'=>'New Delhi',   'region'=>'Asia',    'subregion'=>'Southern Asia',  'timezone'=>'UTC+5:30','language'=>'Hindi',      'latitude'=>20.5937,  'longitude'=>78.9629  ],
            ['country_code'=>'MY','country_name'=>'Malaysia',     'currency'=>'Ringgit',          'currency_code'=>'MYR','capital'=>'Kuala Lumpur','region'=>'Asia',    'subregion'=>'Southeast Asia', 'timezone'=>'UTC+8',   'language'=>'Malay',      'latitude'=>4.2105,   'longitude'=>101.9758 ],
            ['country_code'=>'SG','country_name'=>'Singapore',    'currency'=>'Singapore Dollar', 'currency_code'=>'SGD','capital'=>'Singapore',   'region'=>'Asia',    'subregion'=>'Southeast Asia', 'timezone'=>'UTC+8',   'language'=>'English',    'latitude'=>1.3521,   'longitude'=>103.8198 ],
            ['country_code'=>'TH','country_name'=>'Thailand',     'currency'=>'Baht',             'currency_code'=>'THB','capital'=>'Bangkok',     'region'=>'Asia',    'subregion'=>'Southeast Asia', 'timezone'=>'UTC+7',   'language'=>'Thai',       'latitude'=>15.8700,  'longitude'=>100.9925 ],
            ['country_code'=>'VN','country_name'=>'Vietnam',      'currency'=>'Dong',             'currency_code'=>'VND','capital'=>'Hanoi',       'region'=>'Asia',    'subregion'=>'Southeast Asia', 'timezone'=>'UTC+7',   'language'=>'Vietnamese', 'latitude'=>14.0583,  'longitude'=>108.2772 ],
            ['country_code'=>'PH','country_name'=>'Philippines',  'currency'=>'Philippine Peso',  'currency_code'=>'PHP','capital'=>'Manila',      'region'=>'Asia',    'subregion'=>'Southeast Asia', 'timezone'=>'UTC+8',   'language'=>'Filipino',   'latitude'=>12.8797,  'longitude'=>121.7740 ],
            ['country_code'=>'SA','country_name'=>'Saudi Arabia', 'currency'=>'Saudi Riyal',      'currency_code'=>'SAR','capital'=>'Riyadh',      'region'=>'Asia',    'subregion'=>'Western Asia',   'timezone'=>'UTC+3',   'language'=>'Arabic',     'latitude'=>23.8859,  'longitude'=>45.0792  ],
            ['country_code'=>'AE','country_name'=>'UAE',          'currency'=>'UAE Dirham',       'currency_code'=>'AED','capital'=>'Abu Dhabi',   'region'=>'Asia',    'subregion'=>'Western Asia',   'timezone'=>'UTC+4',   'language'=>'Arabic',     'latitude'=>23.4241,  'longitude'=>53.8478  ],
            ['country_code'=>'TR','country_name'=>'Turkey',       'currency'=>'Turkish Lira',     'currency_code'=>'TRY','capital'=>'Ankara',      'region'=>'Asia',    'subregion'=>'Western Asia',   'timezone'=>'UTC+3',   'language'=>'Turkish',    'latitude'=>38.9637,  'longitude'=>35.2433  ],
            ['country_code'=>'TW','country_name'=>'Taiwan',       'currency'=>'New Taiwan Dollar','currency_code'=>'TWD','capital'=>'Taipei',      'region'=>'Asia',    'subregion'=>'East Asia',      'timezone'=>'UTC+8',   'language'=>'Chinese',    'latitude'=>23.6978,  'longitude'=>120.9605 ],
            ['country_code'=>'HK','country_name'=>'Hong Kong',    'currency'=>'HK Dollar',        'currency_code'=>'HKD','capital'=>'Hong Kong',   'region'=>'Asia',    'subregion'=>'East Asia',      'timezone'=>'UTC+8',   'language'=>'Chinese',    'latitude'=>22.3193,  'longitude'=>114.1694 ],
            ['country_code'=>'DE','country_name'=>'Germany',      'currency'=>'Euro',             'currency_code'=>'EUR','capital'=>'Berlin',      'region'=>'Europe',  'subregion'=>'Central Europe', 'timezone'=>'UTC+1',   'language'=>'German',     'latitude'=>51.1657,  'longitude'=>10.4515  ],
            ['country_code'=>'GB','country_name'=>'United Kingdom','currency'=>'British Pound',   'currency_code'=>'GBP','capital'=>'London',      'region'=>'Europe',  'subregion'=>'Northern Europe','timezone'=>'UTC+0',   'language'=>'English',    'latitude'=>55.3781,  'longitude'=>-3.4360  ],
            ['country_code'=>'FR','country_name'=>'France',       'currency'=>'Euro',             'currency_code'=>'EUR','capital'=>'Paris',       'region'=>'Europe',  'subregion'=>'Western Europe', 'timezone'=>'UTC+1',   'language'=>'French',     'latitude'=>46.2276,  'longitude'=>2.2137   ],
            ['country_code'=>'NL','country_name'=>'Netherlands',  'currency'=>'Euro',             'currency_code'=>'EUR','capital'=>'Amsterdam',   'region'=>'Europe',  'subregion'=>'Western Europe', 'timezone'=>'UTC+1',   'language'=>'Dutch',      'latitude'=>52.1326,  'longitude'=>5.2913   ],
            ['country_code'=>'IT','country_name'=>'Italy',        'currency'=>'Euro',             'currency_code'=>'EUR','capital'=>'Rome',        'region'=>'Europe',  'subregion'=>'Southern Europe','timezone'=>'UTC+1',   'language'=>'Italian',    'latitude'=>41.8719,  'longitude'=>12.5674  ],
            ['country_code'=>'ES','country_name'=>'Spain',        'currency'=>'Euro',             'currency_code'=>'EUR','capital'=>'Madrid',      'region'=>'Europe',  'subregion'=>'Southern Europe','timezone'=>'UTC+1',   'language'=>'Spanish',    'latitude'=>40.4637,  'longitude'=>-3.7492  ],
            ['country_code'=>'RU','country_name'=>'Russia',       'currency'=>'Russian Ruble',    'currency_code'=>'RUB','capital'=>'Moscow',      'region'=>'Europe',  'subregion'=>'Eastern Europe', 'timezone'=>'UTC+3',   'language'=>'Russian',    'latitude'=>61.5240,  'longitude'=>105.3188 ],
            ['country_code'=>'BE','country_name'=>'Belgium',      'currency'=>'Euro',             'currency_code'=>'EUR','capital'=>'Brussels',    'region'=>'Europe',  'subregion'=>'Western Europe', 'timezone'=>'UTC+1',   'language'=>'Dutch',      'latitude'=>50.5039,  'longitude'=>4.4699   ],
            ['country_code'=>'SE','country_name'=>'Sweden',       'currency'=>'Swedish Krona',    'currency_code'=>'SEK','capital'=>'Stockholm',   'region'=>'Europe',  'subregion'=>'Northern Europe','timezone'=>'UTC+1',   'language'=>'Swedish',    'latitude'=>60.1282,  'longitude'=>18.6435  ],
            ['country_code'=>'US','country_name'=>'United States','currency'=>'US Dollar',        'currency_code'=>'USD','capital'=>'Washington DC','region'=>'Americas','subregion'=>'North America','timezone'=>'UTC-5',   'language'=>'English',    'latitude'=>37.0902,  'longitude'=>-95.7129 ],
            ['country_code'=>'CA','country_name'=>'Canada',       'currency'=>'Canadian Dollar',  'currency_code'=>'CAD','capital'=>'Ottawa',      'region'=>'Americas','subregion'=>'North America','timezone'=>'UTC-5',   'language'=>'English',    'latitude'=>56.1304,  'longitude'=>-106.3468],
            ['country_code'=>'MX','country_name'=>'Mexico',       'currency'=>'Mexican Peso',     'currency_code'=>'MXN','capital'=>'Mexico City', 'region'=>'Americas','subregion'=>'North America','timezone'=>'UTC-6',   'language'=>'Spanish',    'latitude'=>23.6345,  'longitude'=>-102.5528],
            ['country_code'=>'BR','country_name'=>'Brazil',       'currency'=>'Brazilian Real',   'currency_code'=>'BRL','capital'=>'Brasília',    'region'=>'Americas','subregion'=>'South America','timezone'=>'UTC-3',   'language'=>'Portuguese', 'latitude'=>-14.2350, 'longitude'=>-51.9253 ],
            ['country_code'=>'AR','country_name'=>'Argentina',    'currency'=>'Argentine Peso',   'currency_code'=>'ARS','capital'=>'Buenos Aires','region'=>'Americas','subregion'=>'South America','timezone'=>'UTC-3',   'language'=>'Spanish',    'latitude'=>-38.4161, 'longitude'=>-63.6167 ],
            ['country_code'=>'ZA','country_name'=>'South Africa', 'currency'=>'Rand',             'currency_code'=>'ZAR','capital'=>'Pretoria',   'region'=>'Africa',  'subregion'=>'Southern Africa','timezone'=>'UTC+2',   'language'=>'Afrikaans',  'latitude'=>-30.5595, 'longitude'=>22.9375  ],
            ['country_code'=>'NG','country_name'=>'Nigeria',      'currency'=>'Naira',            'currency_code'=>'NGN','capital'=>'Abuja',       'region'=>'Africa',  'subregion'=>'Western Africa', 'timezone'=>'UTC+1',   'language'=>'English',    'latitude'=>9.0820,   'longitude'=>8.6753   ],
            ['country_code'=>'EG','country_name'=>'Egypt',        'currency'=>'Egyptian Pound',   'currency_code'=>'EGP','capital'=>'Cairo',       'region'=>'Africa',  'subregion'=>'Northern Africa','timezone'=>'UTC+2',   'language'=>'Arabic',     'latitude'=>26.8206,  'longitude'=>30.8025  ],
            ['country_code'=>'AU','country_name'=>'Australia',    'currency'=>'Australian Dollar','currency_code'=>'AUD','capital'=>'Canberra',    'region'=>'Oceania', 'subregion'=>'Australia & NZ', 'timezone'=>'UTC+10',  'language'=>'English',    'latitude'=>-25.2744, 'longitude'=>133.7751 ],
            ['country_code'=>'NZ','country_name'=>'New Zealand',  'currency'=>'NZ Dollar',        'currency_code'=>'NZD','capital'=>'Wellington',  'region'=>'Oceania', 'subregion'=>'Australia & NZ', 'timezone'=>'UTC+12',  'language'=>'English',    'latitude'=>-40.9006, 'longitude'=>174.8860 ],
        ];

        $count = 0;
        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['country_code' => $country['country_code']],
                array_merge($country, ['created_at' => now(), 'updated_at' => now()])
            );
            $count++;
        }

        $this->command->info("✓ {$count} countries seeded from fallback data.");
    }
}