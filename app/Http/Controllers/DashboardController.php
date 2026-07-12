<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Port;
use App\Models\RiskScore;
use App\Models\Shipment;
use App\Models\Supplier;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Dashboard
        $stats = [
            'shipments' => Shipment::count(),
            'suppliers' => Supplier::count(),
            'countries' => Country::count(),
            'ports' => Port::count(),
        ];

        // Shipment terbaru
        $recentShipments = Shipment::latest()->take(5)->get();

        // Risk Alert
        $riskAlerts = RiskScore::latest()->take(5)->get();

        // Data untuk peta
        $shipmentsMap = Shipment::select(
            'shipment_code',
            'latitude',
            'longitude',
            'status',
            'risk_level'
        )->get();

        return view('dashboard.index', compact(
            'stats',
            'recentShipments',
            'riskAlerts',
            'shipmentsMap'
        ));
    }
}