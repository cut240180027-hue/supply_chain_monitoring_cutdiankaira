<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    /**
     * Daftar kota pelabuhan penting untuk supply chain monitoring.
     */
    private array $ports = [
        ['city' => 'Jakarta',     'country' => 'Indonesia',   'lat' => -6.2088,  'lon' => 106.8456,  'flag' => '🇮🇩'],
        ['city' => 'Singapore',   'country' => 'Singapore',   'lat' => 1.3521,   'lon' => 103.8198,  'flag' => '🇸🇬'],
        ['city' => 'Shanghai',    'country' => 'China',       'lat' => 31.2304,  'lon' => 121.4737,  'flag' => '🇨🇳'],
        ['city' => 'Dubai',       'country' => 'UAE',         'lat' => 25.2048,  'lon' => 55.2708,   'flag' => '🇦🇪'],
        ['city' => 'Rotterdam',   'country' => 'Netherlands', 'lat' => 51.9225,  'lon' => 4.4792,    'flag' => '🇳🇱'],
        ['city' => 'Los Angeles', 'country' => 'USA',         'lat' => 33.7490,  'lon' => -118.1937, 'flag' => '🇺🇸'],
        ['city' => 'Mumbai',      'country' => 'India',       'lat' => 18.9388,  'lon' => 72.8354,   'flag' => '🇮🇳'],
        ['city' => 'Tokyo',       'country' => 'Japan',       'lat' => 35.6762,  'lon' => 139.6503,  'flag' => '🇯🇵'],
        ['city' => 'Sydney',      'country' => 'Australia',   'lat' => -33.8688, 'lon' => 151.2093,  'flag' => '🇦🇺'],
        ['city' => 'Hamburg',     'country' => 'Germany',     'lat' => 53.5753,  'lon' => 10.0153,   'flag' => '🇩🇪'],
        ['city' => 'Surabaya',    'country' => 'Indonesia',   'lat' => -7.2575,  'lon' => 112.7521,  'flag' => '🇮🇩'],
        ['city' => 'Hong Kong',   'country' => 'China',       'lat' => 22.3193,  'lon' => 114.1694,  'flag' => '🇭🇰'],
    ];

    public function index(Request $request)
    {
        $selectedCity = $request->get('city', 'Jakarta');

        $portData = collect($this->ports)->firstWhere('city', $selectedCity)
                    ?? $this->ports[0];

        $lat = $portData['lat'];
        $lon = $portData['lon'];

        $response = Http::timeout(15)->get('https://api.open-meteo.com/v1/forecast', [
            'latitude'      => $lat,
            'longitude'     => $lon,
            'current'       => 'temperature_2m,relative_humidity_2m,wind_speed_10m,precipitation,weather_code,apparent_temperature,surface_pressure',
            'hourly'        => 'temperature_2m,precipitation_probability,wind_speed_10m',
            'daily'         => 'weather_code,temperature_2m_max,temperature_2m_min,precipitation_sum,wind_speed_10m_max,sunrise,sunset',
            'timezone'      => 'auto',
            'forecast_days' => 7,
        ]);

        $weather = null;
        $error   = null;

        if ($response->successful()) {
            $weather = $response->json();
        } else {
            $error = 'Gagal mengambil data cuaca. Coba lagi nanti.';
        }

        return view('weather.index', [
            'ports'        => $this->ports,
            'selectedCity' => $selectedCity,
            'portData'     => $portData,
            'weather'      => $weather,
            'error'        => $error,
        ]);
    }

    public static function weatherCodeInfo(int $code): array
    {
        $map = [
            0  => ['label' => 'Cerah',               'icon' => 'bi-sun',                   'color' => '#f59e0b'],
            1  => ['label' => 'Umumnya Cerah',        'icon' => 'bi-sun',                   'color' => '#f59e0b'],
            2  => ['label' => 'Berawan Sebagian',     'icon' => 'bi-cloud-sun',             'color' => '#6b7280'],
            3  => ['label' => 'Mendung',              'icon' => 'bi-cloud',                 'color' => '#6b7280'],
            45 => ['label' => 'Berkabut',             'icon' => 'bi-cloud-fog2',            'color' => '#9ca3af'],
            48 => ['label' => 'Kabut Tebal',          'icon' => 'bi-cloud-fog2',            'color' => '#9ca3af'],
            51 => ['label' => 'Gerimis Ringan',       'icon' => 'bi-cloud-drizzle',         'color' => '#3b82f6'],
            53 => ['label' => 'Gerimis Sedang',       'icon' => 'bi-cloud-drizzle',         'color' => '#3b82f6'],
            55 => ['label' => 'Gerimis Lebat',        'icon' => 'bi-cloud-drizzle',         'color' => '#3b82f6'],
            61 => ['label' => 'Hujan Ringan',         'icon' => 'bi-cloud-rain',            'color' => '#2563eb'],
            63 => ['label' => 'Hujan Sedang',         'icon' => 'bi-cloud-rain',            'color' => '#2563eb'],
            65 => ['label' => 'Hujan Lebat',          'icon' => 'bi-cloud-rain-heavy',      'color' => '#1d4ed8'],
            71 => ['label' => 'Salju Ringan',         'icon' => 'bi-cloud-snow',            'color' => '#93c5fd'],
            73 => ['label' => 'Salju Sedang',         'icon' => 'bi-cloud-snow',            'color' => '#93c5fd'],
            75 => ['label' => 'Salju Lebat',          'icon' => 'bi-cloud-snow',            'color' => '#93c5fd'],
            80 => ['label' => 'Hujan Shower',         'icon' => 'bi-cloud-rain',            'color' => '#2563eb'],
            81 => ['label' => 'Hujan Shower Sedang',  'icon' => 'bi-cloud-rain',            'color' => '#2563eb'],
            82 => ['label' => 'Hujan Shower Lebat',   'icon' => 'bi-cloud-rain-heavy',      'color' => '#1d4ed8'],
            95 => ['label' => 'Badai Petir',          'icon' => 'bi-cloud-lightning-rain',  'color' => '#7c3aed'],
            96 => ['label' => 'Badai + Hujan Es',     'icon' => 'bi-cloud-lightning-rain',  'color' => '#7c3aed'],
            99 => ['label' => 'Badai Besar',          'icon' => 'bi-cloud-lightning-rain',  'color' => '#7c3aed'],
        ];
        return $map[$code] ?? ['label' => 'Tidak Diketahui', 'icon' => 'bi-question-circle', 'color' => '#9ca3af'];
    }
}