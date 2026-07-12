<?php

namespace App\Http\Controllers;

use App\Models\RiskScore;
use App\Models\Shipment;
use Illuminate\Http\Request;

class RiskController extends Controller
{
    /**
     * Halaman daftar risk score semua shipment
     */
    public function index()
    {
        $riskScores = RiskScore::with(['shipment.originPort', 'shipment.destinationPort',
                                       'shipment.originCountry', 'shipment.destinationCountry'])
            ->orderBy('total_score', 'desc')
            ->paginate(15);

        // Statistik ringkasan
        $stats = [
            'high'   => RiskScore::where('risk_level', 'High')->count(),
            'medium' => RiskScore::where('risk_level', 'Medium')->count(),
            'low'    => RiskScore::where('risk_level', 'Low')->count(),
            'avg'    => round(RiskScore::avg('total_score') ?? 0),
        ];

        return view('risk.index', compact('riskScores', 'stats'));
    }

    /**
     * Hitung ulang risk score untuk satu shipment
     */
    public function recalculate(Shipment $shipment)
    {
        $shipment->recalculateRiskScore();

        return redirect()->back()->with('success', "Risk score untuk {$shipment->shipment_code} berhasil diperbarui.");
    }

    /**
     * Hitung ulang semua shipment aktif
     */
    public function recalculateAll()
    {
        $shipments = Shipment::where('status', '!=', 'Delivered')->get();
        $count = 0;
        foreach ($shipments as $shipment) {
            $shipment->recalculateRiskScore();
            $count++;
        }

        return redirect()->route('risk.index')
            ->with('success', "Risk score untuk {$count} shipment aktif berhasil diperbarui.");
    }

    /**
     * Detail skor risiko satu shipment
     */
    public function show(Shipment $shipment)
    {
        $shipment->load(['riskScore', 'originPort', 'destinationPort',
                         'originCountry', 'destinationCountry', 'supplier']);

        return view('risk.show', compact('shipment'));
    }

    /**
     * API untuk kalkulator manual (dari form)
     */
    public function calculate(Request $request)
    {
        $weather  = (float) $request->weather;
        $currency = (float) $request->currency;
        $port     = (float) $request->port;
        $geo      = (float) $request->geopolitic;

        $score = ($weather * 0.30) + ($currency * 0.25) + ($port * 0.20) + ($geo * 0.25);
        $score = round($score);

        if ($score < 40) {
            $status = 'Low';
        } elseif ($score < 70) {
            $status = 'Medium';
        } else {
            $status = 'High';
        }

        return response()->json([
            'score'  => $score,
            'status' => $status
        ]);
    }
}