<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Port;
use App\Models\RiskScore;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function countries()
    {
        return response()->json(Country::all());
    }

    public function risk()
    {
        return response()->json(RiskScore::with('shipment')->get());
    }

    public function ports()
    {
        return response()->json(Port::with('country')->get());
    }

    public function currency()
    {
        try {
            $response = Http::timeout(5)->get('https://open-er-api.com/v6/latest/USD');
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data kurs'], 500);
        }
    }

    public function news()
    {
        try {
            $response = Http::timeout(5)->get('https://feeds.bbci.co.uk/news/world/rss.xml');
            if ($response->successful()) {
                $xml = @simplexml_load_string($response->body());
                $articles = [];
                if ($xml && isset($xml->channel->item)) {
                    foreach ($xml->channel->item as $item) {
                        $articles[] = [
                            'title' => (string)$item->title,
                            'description' => (string)$item->description,
                            'link' => (string)$item->link,
                            'pubDate' => (string)$item->pubDate,
                        ];
                    }
                }
                return response()->json($articles);
            }
        } catch (\Exception $e) {}

        return response()->json([]);
    }
}