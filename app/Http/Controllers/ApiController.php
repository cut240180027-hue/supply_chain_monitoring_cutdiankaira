<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function sync()
    {
        $weather = Http::get('https://api.open-meteo.com/...')->json();

        $exchange = Http::get('https://api.exchangerate-api.com/...')->json();

        $country = Http::get('https://restcountries.com/v3.1/all')->json();

        $economy = Http::get('https://api.worldbank.org/...')->json();

        $news = Http::get('https://gnews.io/api/...')->json();

        return response()->json([
            'weather' => $weather,
            'exchange' => $exchange,
            'country' => $country,
            'economy' => $economy,
            'news' => $news
        ]);
    }
}