<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class PortController extends Controller
{
    public function sync()
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Laravel',
        ])->get('https://raw.githubusercontent.com/tayljordan/ports/main/ports.json');

        if (!$response->successful()) {
            return redirect()->route('ports.index')
                ->with('error', 'Gagal mengambil data pelabuhan API.');
        }

        $data = $response->json();
        if (!isset($data['ports']) || !is_array($data['ports'])) {
            return redirect()->route('ports.index')
                ->with('error', 'Format data API pelabuhan tidak valid.');
        }

        // Cache countries untuk mempercepat pencarian
        $countryCache = [];
        foreach (Country::all() as $c) {
            $countryCache[strtolower($c->country_name)] = $c->id;
            $countryCache[strtolower($c->country_code)] = $c->id;
        }

        // Manual country mappings untuk sinkronisasi nama
        $mappings = [
            'u.s.a.' => 'united states',
            'united states of america' => 'united states',
            'usa' => 'united states',
            
            'u.k.' => 'united kingdom',
            'uk' => 'united kingdom',
            'great britain' => 'united kingdom',
            'virgin is. (u.k.)' => 'british virgin islands',
            
            'u.a.e.' => 'united arab emirates',
            
            'turkey' => 'türkiye',
            
            'u.s. virgin islands' => 'united states virgin islands',
            'virgin is. (u.s.a.)' => 'united states virgin islands',
            
            'côte d\'ivoire' => 'ivory coast',
            'cote d\'ivoire' => 'ivory coast',
            
            'east timor' => 'timor-leste',
            
            'cabo verde' => 'cape verde',
            
            'st. lucia' => 'saint lucia',
            
            'st. vincent' => 'saint vincent and the grenadines',
            
            'bonaire, sint eustatius and saba' => 'caribbean netherlands',
            'cook is.' => 'cook islands',
            'turks and caicos is.' => 'turks and caicos islands',
            'marshall is.' => 'marshall islands',
            'pitcairn is.' => 'pitcairn islands',
        ];

        foreach ($mappings as $synonym => $target) {
            if (isset($countryCache[$target])) {
                $countryCache[$synonym] = $countryCache[$target];
            }
        }

        $count = 0;

        // Gunakan database transaction agar proses insert/update ribuan record sangat cepat
        DB::transaction(function () use ($data, $countryCache, &$count) {
            foreach ($data['ports'] as $item) {
                // Pastikan koordinat dan nama valid
                if (empty($item['wpi_port_name']) || !isset($item['latitude']) || !isset($item['longitude'])) {
                    continue;
                }

                $rawCountry = strtolower(trim($item['country'] ?? ''));
                if (empty($rawCountry)) {
                    continue;
                }

                // Cari country_id dari cache
                $countryId = $countryCache[$rawCountry] ?? null;

                // Jika tidak ketemu langsung, coba cari dengan pencarian partial di cache keys
                if (!$countryId) {
                    foreach ($countryCache as $name => $id) {
                        if (str_contains($name, $rawCountry) || str_contains($rawCountry, $name)) {
                            $countryId = $id;
                            break;
                        }
                    }
                }

                // Jika tetap tidak ketemu, lewati port ini (karena foreign key country_id wajib)
                if (!$countryId) {
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

        return redirect()->route('ports.index')
            ->with('success', "Data pelabuhan berhasil disinkronkan. {$count} pelabuhan diperbarui.");
    }

    public function index()
    {
        $ports = Port::with('country')->paginate(10);

        return view('ports.index', compact('ports'));
    }

    public function create()
    {
        $countries = Country::orderBy('country_name')->get();

        return view('ports.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'port_name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        Port::create($request->all());

        return redirect()->route('ports.index')
            ->with('success', 'Port berhasil ditambahkan.');
    }

    public function show(Port $port)
    {
        return view('ports.show', compact('port'));
    }

    public function edit(Port $port)
    {
        $countries = Country::orderBy('country_name')->get();

        return view('ports.edit', compact('port', 'countries'));
    }

    public function update(Request $request, Port $port)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'port_name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $port->update($request->all());

        return redirect()->route('ports.index')
            ->with('success', 'Port berhasil diperbarui.');
    }

    public function destroy(Port $port)
    {
        $port->delete();

        return redirect()->route('ports.index')
            ->with('success', 'Port berhasil dihapus.');
    }
}