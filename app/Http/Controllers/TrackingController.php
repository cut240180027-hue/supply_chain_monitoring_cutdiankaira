<?php

namespace App\Http\Controllers;

use App\Models\Shipment;

class TrackingController extends Controller
{
    public function index()
    {
        $shipments = Shipment::all();

        return view('tracking.index', compact('shipments'));
    }
}