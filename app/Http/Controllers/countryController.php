<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CountryController extends Controller
{

public function sync()
{
    $response = Http::withHeaders([
        'User-Agent' => 'Laravel',
    ])->get(
        'https://raw.githubusercontent.com/mledoze/countries/master/countries.json'
    );

    if (!$response->successful()) {
        return redirect()->route('countries.index')
            ->with('error', 'Gagal mengambil data API. Status: ' . $response->status());
    }

    $data = $response->json();

    if (!is_array($data)) {
        return redirect()->route('countries.index')
            ->with('error', 'Response API tidak valid.');
    }

    $count = 0;

    foreach ($data as $item) {

        // Lewati item yang tidak memiliki field wajib
        if (empty($item['cca2']) || empty($item['name']['common'])) {
            continue;
        }

        // kode mata uang (USD, IDR, JPY, dll)
        $currencyCode = '-';
        $currencyName = '-';

        if (!empty($item['currencies'])) {
            $currencyCode = array_key_first($item['currencies']);
            $currencyName = current($item['currencies'])['name'] ?? '-';
        }

        // capital bisa null atau array kosong dari API
        $capital = '-';
        if (!empty($item['capital']) && is_array($item['capital'])) {
            $capital = $item['capital'][0];
        }

        // timezone pertama
        $timezone = '-';
        if (!empty($item['timezones']) && is_array($item['timezones'])) {
            $timezone = $item['timezones'][0];
        }

        // bahasa pertama
        $language = '-';
        if (!empty($item['languages']) && is_array($item['languages'])) {
            $language = array_values($item['languages'])[0];
        }

        // koordinat
        $latitude  = isset($item['latlng'][0]) ? $item['latlng'][0] : null;
        $longitude = isset($item['latlng'][1]) ? $item['latlng'][1] : null;

        Country::updateOrCreate(
            [
                'country_code' => $item['cca2'],
            ],
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

    return redirect()
        ->route('countries.index')
        ->with('success', "REST Countries berhasil disinkronkan. {$count} negara diperbarui.");
}
    public function index()
    {
        $countries = Country::latest()->paginate(10);

        return view('countries.index', compact('countries'));
    }

    public function create()
    {
        return view('countries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_code' => 'required|unique:countries',
            'country_name' => 'required',
            'currency' => 'required',
            'currency_code' => 'required',
        ]);

        Country::create($request->all());

        return redirect()->route('countries.index')
            ->with('success', 'Country berhasil ditambahkan.');
    }

    public function show(Country $country)
    {
        return view('countries.show', compact('country'));
    }

    public function edit(Country $country)
    {
        return view('countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $request->validate([
            'country_code' => 'required|unique:countries,country_code,' . $country->id,
            'country_name' => 'required',
            'currency' => 'required',
            'currency_code' => 'required',
        ]);

        $country->update($request->all());

        return redirect()->route('countries.index')
            ->with('success', 'Country berhasil diperbarui.');
    }

    public function destroy(Country $country)
    {
        $country->delete();

        return redirect()->route('countries.index')
            ->with('success', 'Country berhasil dihapus.');
    }
}