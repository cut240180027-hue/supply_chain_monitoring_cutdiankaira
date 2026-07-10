<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class EconomicController extends Controller
{
    public function index()
    {
        $economy = Http::get('https://api.worldbank.org/...')->json();

        return response()->json($economy);
    }
}