<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    public function index()
    {
        $news = Http::get('https://gnews.io/api/...')->json();

        return response()->json($news);
    }
}