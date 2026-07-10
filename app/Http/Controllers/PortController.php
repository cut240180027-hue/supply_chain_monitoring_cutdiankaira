<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class PortController extends Controller
{
    public function index()
    {
        $ports = Http::get('https://marine-api.com/...')->json();

        return response()->json($ports);
    }
}