<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Port;
use App\Models\RiskScore;
use App\Models\Shipment;
use App\Models\Supplier;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung ulang skor risiko untuk semua shipment aktif agar real-time
        $activeShipments = Shipment::where('status', '!=', 'Delivered')->get();
        foreach ($activeShipments as $shipment) {
            $shipment->recalculateRiskScore();
        }

        // 2. Statistik Dashboard
        $stats = [
            'shipments' => Shipment::count(),
            'suppliers' => Supplier::count(),
            'countries' => Country::count(),
            'ports'     => Port::count(),
        ];

        // 3. Shipment terbaru beserta relasi skor risiko lengkap
        $recentShipments = Shipment::with(['riskScore', 'originPort', 'destinationPort'])
            ->latest()
            ->take(5)
            ->get();

        // 4. Data koordinat semua negara dari database untuk peta
        $countriesMap = Country::select(
            'id',
            'country_name',
            'country_code',
            'capital',
            'latitude',
            'longitude',
            'region',
            'currency_code'
        )
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get();

        // 5. Data koordinat shipment untuk peta (marker overlay terpisah)
        $shipmentsMap = Shipment::select(
            'id',
            'shipment_code',
            'latitude',
            'longitude',
            'status',
            'risk_level'
        )->whereNotNull('latitude')->whereNotNull('longitude')->get();

        // 5. Data Tabel Cuaca Hub Pelabuhan Utama dari Open-Meteo
        $hubs = [
            ['name' => 'Shanghai Port (CN)',    'lat' => 31.2304, 'lon' => 121.4737],
            ['name' => 'Singapore Port (SG)',   'lat' => 1.3521,  'lon' => 103.8198],
            ['name' => 'Jakarta Port (ID)',     'lat' => -6.2088, 'lon' => 106.8456],
            ['name' => 'Rotterdam Port (NL)',   'lat' => 51.9225, 'lon' => 4.4792],
        ];

        $weatherHubs = [];
        foreach ($hubs as $hub) {
            try {
                $response = Http::timeout(4)->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude'  => $hub['lat'],
                    'longitude' => $hub['lon'],
                    'current'   => 'temperature_2m,relative_humidity_2m,wind_speed_10m,precipitation,weather_code',
                    'timezone'  => 'auto'
                ]);

                if ($response->successful()) {
                    $wData = $response->json();
                    $cur = $wData['current'];
                    $weatherHubs[] = [
                        'name' => $hub['name'],
                        'temp' => $cur['temperature_2m'],
                        'wind' => $cur['wind_speed_10m'],
                        'rain' => $cur['precipitation'],
                        'code' => $cur['weather_code'],
                    ];
                } else {
                    throw new \Exception();
                }
            } catch (\Exception $e) {
                // Fallback static jika API timeout
                $weatherHubs[] = [
                    'name' => $hub['name'],
                    'temp' => 28.0,
                    'wind' => 12.0,
                    'rain' => 0.0,
                    'code' => 1,
                ];
            }
        }

        // 6. Grafik Kurs (Exchange Rates) dari API ExchangeRate
        $ratesData = [];
        try {
            $rateResponse = Http::timeout(4)->get('https://open-er-api.com/v6/latest/USD');
            if ($rateResponse->successful()) {
                $rJson = $rateResponse->json();
                $rates = $rJson['rates'] ?? [];
                $ratesData = [
                    'IDR' => $rates['IDR'] ?? 15500,
                    'EUR' => $rates['EUR'] ?? 0.92,
                    'SGD' => $rates['SGD'] ?? 1.34,
                    'CNY' => $rates['CNY'] ?? 7.15,
                    'JPY' => $rates['JPY'] ?? 150,
                ];
            } else {
                throw new \Exception();
            }
        } catch (\Exception $e) {
            $ratesData = ['IDR' => 15500, 'EUR' => 0.92, 'SGD' => 1.34, 'CNY' => 7.15, 'JPY' => 150];
        }

        return view('dashboard.index', compact(
            'stats',
            'recentShipments',
            'shipmentsMap',
            'countriesMap',
            'weatherHubs',
            'ratesData'
        ));
    }

    public function readAllNotifications()
    {
        \App\Models\Notification::where('is_read', false)->update(['is_read' => true]);
        return redirect()->back()->with('success', 'Semua notifikasi ditandai telah dibaca.');
    }
}