<?php

namespace App\Http\Controllers;

use App\Models\Shipment;

class TrackingController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with([
            'supplier',
            'originCountry',
            'destinationCountry',
            'originPort',
            'destinationPort'
        ])->get();

        return view('tracking.index', compact('shipments'));
    }
}