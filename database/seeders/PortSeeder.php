<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Port;
use App\Models\Country;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class PortSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Fetching ports from World Port Index API...');

        // Build country lookup cache: name -> id, code -> id
        $countryCache = [];
        foreach (Country::all() as $c) {
            $countryCache[strtolower($c->country_name)] = $c->id;
            $countryCache[strtolower($c->country_code)] = $c->id;
        }

        // Manual synonym mappings for country names in the ports API
        $mappings = [
            'u.s.a.'                            => 'united states',
            'united states of america'          => 'united states',
            'usa'                               => 'united states',
            'u.k.'                              => 'united kingdom',
            'uk'                                => 'united kingdom',
            'great britain'                     => 'united kingdom',
            'u.a.e.'                            => 'united arab emirates',
            'turkey'                            => 'türkiye',
            'turkiye'                           => 'türkiye',
            'south korea'                       => 'south korea',
            'republic of korea'                 => 'south korea',
            'korea, south'                      => 'south korea',
            'taiwan'                            => 'taiwan',
            'democratic republic of the congo'  => 'dr congo',
            'uae'                               => 'united arab emirates',
            'vietnam'                           => 'vietnam',
            'viet nam'                          => 'vietnam',
            'côte d\'ivoire'                    => 'ivory coast',
            'cote d\'ivoire'                    => 'ivory coast',
            'east timor'                        => 'timor-leste',
            'cabo verde'                        => 'cape verde',
            'st. lucia'                         => 'saint lucia',
            'st. vincent'                       => 'saint vincent and the grenadines',
            'bonaire, sint eustatius and saba'  => 'caribbean netherlands',
            'cook is.'                          => 'cook islands',
            'turks and caicos is.'              => 'turks and caicos islands',
            'marshall is.'                      => 'marshall islands',
            'pitcairn is.'                      => 'pitcairn islands',
            'virgin is. (u.k.)'                 => 'british virgin islands',
            'virgin is. (u.s.a.)'               => 'united states virgin islands',
            'u.s. virgin islands'               => 'united states virgin islands',
            'hong kong'                         => 'hong kong',
            'macau'                             => 'macau',
            'myanmar'                           => 'myanmar',
            'burma'                             => 'myanmar',
        ];

        foreach ($mappings as $synonym => $target) {
            if (isset($countryCache[$target])) {
                $countryCache[$synonym] = $countryCache[$target];
            }
        }

        try {
            $response = Http::withHeaders(['User-Agent' => 'Laravel'])
                ->timeout(30)
                ->get('https://raw.githubusercontent.com/tayljordan/ports/main/ports.json');

            if (!$response->successful()) {
                $this->command->warn('Port API failed (HTTP ' . $response->status() . '). Seeding fallback ports.');
                $this->seedFallback();
                return;
            }

            $data = $response->json();

            if (!isset($data['ports']) || !is_array($data['ports'])) {
                $this->command->warn('Port API response invalid. Seeding fallback ports.');
                $this->seedFallback();
                return;
            }

            $count = 0;
            $skipped = 0;

            DB::transaction(function () use ($data, $countryCache, &$count, &$skipped) {
                foreach ($data['ports'] as $item) {
                    if (empty($item['wpi_port_name']) || !isset($item['latitude']) || !isset($item['longitude'])) {
                        continue;
                    }

                    $rawCountry = strtolower(trim($item['country'] ?? ''));
                    if (empty($rawCountry)) {
                        $skipped++;
                        continue;
                    }

                    // Direct match
                    $countryId = $countryCache[$rawCountry] ?? null;

                    // Partial match fallback
                    if (!$countryId) {
                        foreach ($countryCache as $name => $id) {
                            if (strlen($rawCountry) >= 3 && (str_contains($name, $rawCountry) || str_contains($rawCountry, $name))) {
                                $countryId = $id;
                                break;
                            }
                        }
                    }

                    if (!$countryId) {
                        $skipped++;
                        continue;
                    }

                    Port::updateOrCreate(
                        [
                            'port_name'  => ucwords(strtolower($item['wpi_port_name'])),
                            'country_id' => $countryId,
                        ],
                        [
                            'latitude'  => (double)$item['latitude'],
                            'longitude' => (double)$item['longitude'],
                        ]
                    );

                    $count++;
                }
            });

            $this->command->info("✓ {$count} ports synced from API. {$skipped} skipped (country not found).");

        } catch (\Exception $e) {
            $this->command->warn('Port API failed: ' . $e->getMessage() . '. Seeding fallback ports.');
            $this->seedFallback();
        }
    }

    /**
     * Fallback: seed major world ports if API is unavailable.
     */
    protected function seedFallback(): void
    {
        // Get country IDs by code
        $getCountry = function(string $code) {
            return Country::where('country_code', $code)->value('id');
        };

        $ports = [
            // China
            ['port_name'=>'Shanghai Port',          'country_code'=>'CN','lat'=>31.2304,  'lon'=>121.4737],
            ['port_name'=>'Shenzhen Port',           'country_code'=>'CN','lat'=>22.5598,  'lon'=>114.2560],
            ['port_name'=>'Ningbo-Zhoushan Port',    'country_code'=>'CN','lat'=>29.8683,  'lon'=>121.5440],
            ['port_name'=>'Qingdao Port',            'country_code'=>'CN','lat'=>36.1110,  'lon'=>120.3010],
            // Indonesia
            ['port_name'=>'Tanjung Priok',           'country_code'=>'ID','lat'=>-6.1049,  'lon'=>106.8800],
            ['port_name'=>'Tanjung Perak',           'country_code'=>'ID','lat'=>-7.2048,  'lon'=>112.7320],
            ['port_name'=>'Belawan',                 'country_code'=>'ID','lat'=>3.7850,   'lon'=>98.6830 ],
            ['port_name'=>'Makassar Port',           'country_code'=>'ID','lat'=>-5.1478,  'lon'=>119.4327],
            // Japan
            ['port_name'=>'Tokyo Port',              'country_code'=>'JP','lat'=>35.6570,  'lon'=>139.7966],
            ['port_name'=>'Yokohama Port',           'country_code'=>'JP','lat'=>35.4437,  'lon'=>139.6380],
            ['port_name'=>'Osaka Port',              'country_code'=>'JP','lat'=>34.6473,  'lon'=>135.4700],
            // South Korea
            ['port_name'=>'Busan Port',              'country_code'=>'KR','lat'=>35.1036,  'lon'=>129.0403],
            ['port_name'=>'Incheon Port',            'country_code'=>'KR','lat'=>37.4531,  'lon'=>126.6095],
            // India
            ['port_name'=>'Mumbai JNPT',             'country_code'=>'IN','lat'=>18.9500,  'lon'=>72.9500 ],
            ['port_name'=>'Chennai Port',            'country_code'=>'IN','lat'=>13.0825,  'lon'=>80.2977 ],
            ['port_name'=>'Mundra Port',             'country_code'=>'IN','lat'=>22.7689,  'lon'=>69.7003 ],
            // Singapore
            ['port_name'=>'Port of Singapore',       'country_code'=>'SG','lat'=>1.2804,   'lon'=>103.8574],
            // Malaysia
            ['port_name'=>'Port Klang',              'country_code'=>'MY','lat'=>3.0059,   'lon'=>101.4024],
            ['port_name'=>'Port of Tanjung Pelepas', 'country_code'=>'MY','lat'=>1.3642,   'lon'=>103.5484],
            // UAE
            ['port_name'=>'Jebel Ali Port',          'country_code'=>'AE','lat'=>25.0113,  'lon'=>55.0617 ],
            // Saudi Arabia
            ['port_name'=>'Jeddah Islamic Port',     'country_code'=>'SA','lat'=>21.5169,  'lon'=>39.1593 ],
            // Netherlands
            ['port_name'=>'Port of Rotterdam',       'country_code'=>'NL','lat'=>51.9244,  'lon'=>4.4777  ],
            // Germany
            ['port_name'=>'Hamburg Port',            'country_code'=>'DE','lat'=>53.5419,  'lon'=>9.9900  ],
            // Belgium
            ['port_name'=>'Port of Antwerp',         'country_code'=>'BE','lat'=>51.2602,  'lon'=>4.3780  ],
            // United Kingdom
            ['port_name'=>'Port of Felixstowe',      'country_code'=>'GB','lat'=>51.9644,  'lon'=>1.3248  ],
            ['port_name'=>'Port of Southampton',     'country_code'=>'GB','lat'=>50.9000,  'lon'=>-1.4000 ],
            // France
            ['port_name'=>'Le Havre Port',           'country_code'=>'FR','lat'=>49.4938,  'lon'=>0.1079  ],
            // United States
            ['port_name'=>'Port of Los Angeles',     'country_code'=>'US','lat'=>33.7395,  'lon'=>-118.2745],
            ['port_name'=>'Port of New York/NJ',     'country_code'=>'US','lat'=>40.6501,  'lon'=>-74.0444],
            ['port_name'=>'Port of Long Beach',      'country_code'=>'US','lat'=>33.7674,  'lon'=>-118.2167],
            // Brazil
            ['port_name'=>'Port of Santos',          'country_code'=>'BR','lat'=>-23.9786, 'lon'=>-46.3024],
            // Australia
            ['port_name'=>'Port of Melbourne',       'country_code'=>'AU','lat'=>-37.8136, 'lon'=>144.9631],
            ['port_name'=>'Port of Sydney',          'country_code'=>'AU','lat'=>-33.8688, 'lon'=>151.2093],
            // Egypt
            ['port_name'=>'Port Said',               'country_code'=>'EG','lat'=>31.2565,  'lon'=>32.3017 ],
            ['port_name'=>'Alexandria Port',         'country_code'=>'EG','lat'=>31.2001,  'lon'=>29.9187 ],
            // South Africa
            ['port_name'=>'Port of Durban',          'country_code'=>'ZA','lat'=>-29.8587, 'lon'=>31.0218 ],
        ];

        $count = 0;
        foreach ($ports as $p) {
            $countryId = Country::where('country_code', $p['country_code'])->value('id');
            if (!$countryId) continue;

            Port::updateOrCreate(
                ['port_name' => $p['port_name'], 'country_id' => $countryId],
                [
                    'latitude'   => $p['lat'],
                    'longitude'  => $p['lon'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            $count++;
        }

        $this->command->info("✓ {$count} fallback ports seeded.");
    }
}