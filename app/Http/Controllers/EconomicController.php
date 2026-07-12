<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\EconomicIndicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EconomicController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua negara dari database
        $countries = Country::orderBy('country_name')->get();

        if ($countries->isEmpty()) {
            return view('economy.index', [
                'countries'       => collect(),
                'selectedCountry' => null,
                'selectedYear'    => 2023,
                'indicator'       => null,
                'error'           => 'Belum ada data negara di database. Silakan lakukan sinkronisasi data negara terlebih dahulu.',
            ]);
        }

        // Tentukan negara terpilih
        $selectedCode = strtoupper($request->get('country', 'ID'));
        $selectedCountry = $countries->firstWhere('country_code', $selectedCode) 
            ?? $countries->firstWhere('country_code', 'ID') 
            ?? $countries->first();

        // Tentukan tahun terpilih (default 2023)
        $selectedYear = (int)$request->get('year', 2023);
        if ($selectedYear < 2010 || $selectedYear > date('Y')) {
            $selectedYear = 2023;
        }

        // Coba ambil data dari DB lokal dulu
        $indicator = EconomicIndicator::where('country_id', $selectedCountry->id)
            ->where('year', $selectedYear)
            ->first();

        $error = null;

        // Jika request memaksa refresh atau data belum ada di lokal, fetch dari World Bank
        if ($request->has('refresh') || !$indicator) {
            $indicatorsMap = [
                'gdp'          => 'NY.GDP.MKTP.CD', // GDP (current US$)
                'inflation'    => 'FP.CPI.TOTL.ZG', // Inflation, consumer prices (annual %)
                'population'   => 'SP.POP.TOTL',    // Population, total
                'export_value' => 'NE.EXP.GNFS.CD', // Exports of goods and services (current US$)
                'import_value' => 'NE.IMP.GNFS.CD', // Imports of goods and services (current US$)
            ];

            $apiData = [];
            $apiSuccess = true;

            foreach ($indicatorsMap as $key => $wbCode) {
                try {
                    // Gunakan API v2 World Bank
                    $url = "http://api.worldbank.org/v2/country/{$selectedCountry->country_code}/indicator/{$wbCode}?format=json&date={$selectedYear}";
                    $response = Http::timeout(8)->get($url);

                    if ($response->successful()) {
                        $resJson = $response->json();
                        // Format World Bank: [0 => metadata, 1 => array of data]
                        if (is_array($resJson) && count($resJson) >= 2 && isset($resJson[1][0])) {
                            $apiData[$key] = $resJson[1][0]['value'];
                        } else {
                            $apiData[$key] = null;
                        }
                    } else {
                        $apiData[$key] = null;
                        $apiSuccess = false;
                    }
                } catch (\Exception $e) {
                    $apiData[$key] = null;
                    $apiSuccess = false;
                }
            }

            // Jika minimal salah satu data berhasil diambil, simpan/update ke database
            if ($apiSuccess || !empty(array_filter($apiData))) {
                $indicator = EconomicIndicator::updateOrCreate(
                    [
                        'country_id' => $selectedCountry->id,
                        'year'       => $selectedYear,
                    ],
                    [
                        'gdp'          => $apiData['gdp'],
                        'inflation'    => $apiData['inflation'],
                        'population'   => $apiData['population'],
                        'export_value' => $apiData['export_value'],
                        'import_value' => $apiData['import_value'],
                    ]
                );
            } else {
                $error = 'Gagal memuat data baru dari API World Bank. Menampilkan data lokal jika tersedia.';
            }
        }

        return view('economy.index', [
            'countries'       => $countries,
            'selectedCountry' => $selectedCountry,
            'selectedYear'    => $selectedYear,
            'indicator'       => $indicator,
            'error'           => $error,
        ]);
    }
}