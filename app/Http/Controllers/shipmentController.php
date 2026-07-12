<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Supplier;
use App\Models\Country;
use App\Models\Port;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    /**
     * Menampilkan daftar shipment.
     */
    public function index()
    {
        $shipments = Shipment::with([
            'supplier',
            'originCountry',
            'destinationCountry',
            'originPort',
            'destinationPort'
        ])->latest()->paginate(10);

        return view('shipment.index', compact('shipments'));
    }

    /**
     * Menampilkan form tambah shipment.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $countries = Country::all();
        $ports = Port::all();

        return view('shipment.create', compact(
            'suppliers',
            'countries',
            'ports'
        ));
    }

    /**
     * Menyimpan shipment baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipment_code' => 'required|unique:shipments',
            'supplier_id' => 'required|exists:suppliers,id',
            'origin_country_id' => 'required|exists:countries,id',
            'destination_country_id' => 'required|exists:countries,id',
            'origin_port_id' => 'required|exists:ports,id',
            'destination_port_id' => 'required|exists:ports,id',
            'vessel_name' => 'required',
            'departure_date' => 'required|date',
            'estimated_arrival' => 'required|date',
            'status' => 'required',
            'risk_level' => 'required',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable'
        ]);

        Shipment::create($request->all());

        return redirect()
            ->route('shipments.index')
            ->with('success', 'Shipment berhasil ditambahkan.');
    }

    /**
     * Detail shipment.
     */
    public function show(Shipment $shipment)
    {
        $shipment->load([
            'supplier',
            'originCountry',
            'destinationCountry',
            'originPort',
            'destinationPort',
            'weatherLogs',
            'exchangeRates',
            'riskScore'
        ]);

        return view('shipment.show', compact('shipment'));
    }

    /**
     * Form edit.
     */
    public function edit(Shipment $shipment)
    {
        $suppliers = Supplier::all();
        $countries = Country::all();
        $ports = Port::all();

        return view('shipment.edit', compact(
            'shipment',
            'suppliers',
            'countries',
            'ports'
        ));
    }

    /**
     * Update shipment.
     */
    public function update(Request $request, Shipment $shipment)
    {
        $request->validate([
            'shipment_code' => 'required|unique:shipments,shipment_code,' . $shipment->id,
            'supplier_id' => 'required|exists:suppliers,id',
            'origin_country_id' => 'required|exists:countries,id',
            'destination_country_id' => 'required|exists:countries,id',
            'origin_port_id' => 'required|exists:ports,id',
            'destination_port_id' => 'required|exists:ports,id',
            'vessel_name' => 'required',
            'departure_date' => 'required|date',
            'estimated_arrival' => 'required|date',
            'status' => 'required',
            'risk_level' => 'required',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable'
        ]);

        $shipment->update($request->all());

        return redirect()
            ->route('shipments.index')
            ->with('success', 'Shipment berhasil diperbarui.');
    }

    /**
     * Hapus shipment.
     */
    public function destroy(Shipment $shipment)
    {
        $shipment->delete();

        return redirect()
            ->route('shipments.index')
            ->with('success', 'Shipment berhasil dihapus.');
    }
}