<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExchangeRateController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua mata uang unik dari database countries
        $countryCurrencies = Country::select('currency_code', 'currency')
            ->whereNotNull('currency_code')
            ->where('currency_code', '!=', '-')
            ->where('currency_code', '!=', '')
            ->distinct()
            ->orderBy('currency_code')
            ->get();

        if ($countryCurrencies->isEmpty()) {
            return view('currency.index', [
                'currencies'   => collect(),
                'baseCurrency' => 'USD',
                'targetRates'  => [],
                'error'        => 'Belum ada data negara/mata uang di database. Silakan lakukan sinkronisasi data negara terlebih dahulu.',
            ]);
        }

        // Tentukan base currency (default USD atau IDR jika ada)
        $baseCurrency = strtoupper($request->get('base', 'USD'));
        
        // Pastikan base currency terdaftar, jika tidak fallback ke USD
        $isValidBase = $countryCurrencies->firstWhere('currency_code', $baseCurrency) !== null;
        if (!$isValidBase) {
            $baseCurrency = 'USD';
        }

        // Fetch data kurs terbaru dari open API
        $response = Http::timeout(10)->get("https://open.er-api.com/v6/latest/{$baseCurrency}");

        $targetRates = [];
        $error = null;
        $lastUpdate = null;

        if ($response->successful()) {
            $data = $response->json();
            $rates = $data['rates'] ?? [];
            $lastUpdate = $data['time_last_update_utc'] ?? null;

            // Filter rates hanya untuk mata uang yang ada di database countries kita
            foreach ($countryCurrencies as $curr) {
                $code = $curr->currency_code;
                if (isset($rates[$code])) {
                    $targetRates[] = [
                        'code' => $code,
                        'name' => $curr->currency,
                        'rate' => $rates[$code],
                    ];
                }
            }
        } else {
            $error = 'Gagal memuat kurs mata uang real-time dari API. Silakan coba beberapa saat lagi.';
        }

        return view('currency.index', [
            'currencies'   => $countryCurrencies,
            'baseCurrency' => $baseCurrency,
            'targetRates'  => $targetRates,
            'lastUpdate'   => $lastUpdate,
            'error'        => $error,
        ]);
    }
}