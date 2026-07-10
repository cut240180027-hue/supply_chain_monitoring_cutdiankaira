<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Port;
use App\Models\RiskScore;
use App\Models\Shipment;
use App\Models\Supplier;
use Illuminate\Http\Request;

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

        $recentShipments = Shipment::latest('created_at')->take(5)->get();
        $riskAlerts = RiskScore::orderByDesc('score')->take(4)->get();

        return view('dashboard', compact('stats', 'recentShipments', 'riskAlerts'));
    }
}