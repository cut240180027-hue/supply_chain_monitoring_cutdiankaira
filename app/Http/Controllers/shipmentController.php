<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    /**
     * Menampilkan daftar shipment.
     */
    public function index()
    {
        $shipments = Shipment::latest()->paginate(10);

        return view('shipment.index', compact('shipments'));
    }

    /**
     * Menampilkan form tambah shipment.
     */
    public function create()
    {
        return view('shipment.create');
    }

    /**
     * Menyimpan shipment baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipment_code'      => 'required|unique:shipments',
            'supplier'           => 'required',
            'origin_country'     => 'required',
            'destination_country'=> 'required',
            'origin_port'        => 'required',
            'destination_port'   => 'required',
            'vessel_name'        => 'required',
            'departure_date'     => 'required|date',
            'estimated_arrival'  => 'required|date',
            'status'             => 'required',
            'risk_level'         => 'required',
            'latitude'           => 'nullable',
            'longitude'          => 'nullable',
            'description'        => 'nullable',
        ]);

        Shipment::create($request->all());

        return redirect()
                ->route('shipments.index')
                ->with('success','Shipment berhasil ditambahkan.');
    }

    /**
     * Detail shipment.
     */
    public function show(Shipment $shipment)
    {
        return view('shipment.show', compact('shipment'));
    }

    /**
     * Form edit.
     */
    public function edit(Shipment $shipment)
    {
        return view('shipment.edit', compact('shipment'));
    }

    /**
     * Update shipment.
     */
    public function update(Request $request, Shipment $shipment)
    {
        $request->validate([
            'shipment_code'      => 'required|unique:shipments,shipment_code,'.$shipment->id,
            'supplier'           => 'required',
            'origin_country'     => 'required',
            'destination_country'=> 'required',
            'origin_port'        => 'required',
            'destination_port'   => 'required',
            'vessel_name'        => 'required',
            'departure_date'     => 'required|date',
            'estimated_arrival'  => 'required|date',
            'status'             => 'required',
            'risk_level'         => 'required',
        ]);

        $shipment->update($request->all());

        return redirect()
                ->route('shipments.index')
                ->with('success','Shipment berhasil diperbarui.');
    }

    /**
     * Hapus shipment.
     */
    public function destroy(Shipment $shipment)
    {
        $shipment->delete();

        return redirect()
                ->route('shipments.index')
                ->with('success','Shipment berhasil dihapus.');
    }
}