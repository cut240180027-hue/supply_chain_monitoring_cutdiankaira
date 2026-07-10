<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class ExchangeRateController extends Controller
{
    public function index()
    {
        $rates = Http::get('https://api.exchangerate-api.com/...')->json();

        return response()->json($rates);
    }
}