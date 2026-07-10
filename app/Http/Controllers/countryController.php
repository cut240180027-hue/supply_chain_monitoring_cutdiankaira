<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Http::get('https://restcountries.com/v3.1/all')->json();

        return view('country.index', compact('countries'));
    }
}