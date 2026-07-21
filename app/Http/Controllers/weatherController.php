<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public static function getFlagEmoji(string $code): string
    {
        if (strlen($code) !== 2) return '';
        $chars = str_split(strtoupper($code));
        return mb_chr(ord($chars[0]) - 65 + 0x1F1E6) . mb_chr(ord($chars[1]) - 65 + 0x1F1E6);
    }

    public function index(Request $request)
    {
        $countries = \App\Models\Country::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderBy('country_name')
            ->get();

        if ($countries->isEmpty()) {
            return view('weather.index', [
                'countries'       => collect(),
                'selectedCountry' => null,
                'weather'         => null,
                'error'           => 'Belum ada data negara di database. Silakan lakukan sinkronisasi data negara terlebih dahulu.',
            ]);
        }

        // Tentukan country terpilih, default Indonesia (ID) jika ada, jika tidak ambil pertama
        $selectedCode = strtoupper($request->get('country', 'ID'));
        $selectedCountry = $countries->firstWhere('country_code', $selectedCode) 
            ?? $countries->firstWhere('country_code', 'ID') 
            ?? $countries->first();

        $lat = $selectedCountry->latitude;
        $lon = $selectedCountry->longitude;

        $weather = null;
        $error   = null;

        try {
            $response = Http::timeout(15)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude'      => $lat,
                'longitude'     => $lon,
                'current'       => 'temperature_2m,relative_humidity_2m,wind_speed_10m,precipitation,weather_code,apparent_temperature,surface_pressure',
                'hourly'        => 'temperature_2m,precipitation_probability,wind_speed_10m',
                'daily'         => 'weather_code,temperature_2m_max,temperature_2m_min,precipitation_sum,wind_speed_10m_max,sunrise,sunset',
                'timezone'      => 'auto',
                'forecast_days' => 7,
            ]);

            if ($response->successful()) {
                $weather = $response->json();
            } else {
                $error = 'Gagal mengambil data cuaca dari API Open-Meteo. Silakan coba beberapa saat lagi.';
            }
        } catch (\Exception $e) {
            $error = 'Tidak dapat terhubung ke layanan cuaca. Periksa koneksi internet Anda dan coba lagi.';
        }

        return view('weather.index', [
            'countries'       => $countries,
            'selectedCountry' => $selectedCountry,
            'weather'         => $weather,
            'error'           => $error,
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