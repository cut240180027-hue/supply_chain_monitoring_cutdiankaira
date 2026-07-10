<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::all();

        return view('shipment.index', compact('shipments'));
    }

    public function show($id)
    {
        $shipment = Shipment::findOrFail($id);

        return view('shipment.show', compact('shipment'));
    }

    public function create()
    {
        return view('shipment.create');
    }

    public function store(Request $request)
    {
        Shipment::create($request->all());

        return redirect()->route('shipment.index');
    }

    public function edit($id)
    {
        $shipment = Shipment::findOrFail($id);

        return view('shipment.edit', compact('shipment'));
    }

    public function update(Request $request, $id)
    {
        $shipment = Shipment::findOrFail($id);

        $shipment->update($request->all());

        return redirect()->route('shipment.index');
    }

    public function destroy($id)
    {
        Shipment::destroy($id);

        return redirect()->route('shipment.index');
    }
}