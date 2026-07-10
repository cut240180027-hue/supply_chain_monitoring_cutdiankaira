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
        $stats = [
            'shipments' => Shipment::count(),
            'suppliers' => Supplier::count(),
            'countries' => Country::count(),
            'ports' => Port::count(),
        ];

        $recentShipments = Shipment::latest()->take(5)->get();
        $riskAlerts = RiskScore::latest()->take(4)->get();

        return view('dashboard.index', compact(
            'stats',
            'recentShipments',
            'riskAlerts'
        ));
    }
}