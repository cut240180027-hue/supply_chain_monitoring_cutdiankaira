<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RiskController extends Controller
{
    public function calculate(Request $request)
    {
        $weather = $request->weather;
        $currency = $request->currency;
        $port = $request->port;
        $geo = $request->geopolitic;

        $score =
            ($weather * 0.30) +
            ($currency * 0.25) +
            ($port * 0.20) +
            ($geo * 0.25);

        if ($score < 40) {
            $status = "Rendah";
        } elseif ($score < 70) {
            $status = "Sedang";
        } else {
            $status = "Tinggi";
        }

        return response()->json([
            'score' => $score,
            'status' => $status
        ]);
    }
}