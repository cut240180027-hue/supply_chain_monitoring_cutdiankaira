<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index()
    {
        $weather = Http::get('https://api.open-meteo.com/...')->json();

        return response()->json($weather);
    }
}