<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\EconomicIndicator;
use App\Services\SentimentAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CountryDashboardController extends Controller
{
    public function index(Request $request)
    {
        $countries = Country::orderBy('country_name')->get();

        if ($countries->isEmpty()) {
            return view('country-dashboard.index', [
                'countries' => collect(),
                'selectedCountry' => null,
                'error' => 'Belum ada data negara di database. Silakan lakukan sinkronisasi data negara terlebih dahulu.',
            ]);
        }

        $selectedCode = strtoupper($request->get('country', 'ID'));
        $selectedCountry = $countries->firstWhere('country_code', $selectedCode)
            ?? $countries->firstWhere('country_code', 'ID')
            ?? $countries->first();

        // 1. Economic Indicators (Latest year available)
        $economy = EconomicIndicator::where('country_id', $selectedCountry->id)
            ->orderBy('year', 'desc')
            ->first();

        // 2. Real-time Weather
        $weather = null;
        $weatherError = null;
        if ($selectedCountry->latitude && $selectedCountry->longitude) {
            try {
                $weatherResponse = Http::timeout(5)->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => $selectedCountry->latitude,
                    'longitude' => $selectedCountry->longitude,
                    'current' => 'temperature_2m,relative_humidity_2m,wind_speed_10m,precipitation,weather_code',
                    'timezone' => 'auto'
                ]);
                if ($weatherResponse->successful()) {
                    $weather = $weatherResponse->json('current');
                }
            } catch (\Exception $e) {
                $weatherError = 'Gagal memuat cuaca real-time.';
            }
        }

        // 3. Currency Rate relative to USD
        $exchangeRate = 1.0;
        if ($selectedCountry->currency_code && $selectedCountry->currency_code !== '-') {
            try {
                $rateResponse = Http::timeout(4)->get('https://open-er-api.com/v6/latest/USD');
                if ($rateResponse->successful()) {
                    $rates = $rateResponse->json('rates');
                    $exchangeRate = $rates[$selectedCountry->currency_code] ?? null;
                }
            } catch (\Exception $e) {}
        }

        // 4. News Sentiment for the selected country
        $matchedNews = [];
        $sentimentStats = ['Positive' => 0, 'Neutral' => 100, 'Negative' => 0, 'total' => 0];
        try {
            $feedUrl = "https://feeds.bbci.co.uk/news/world/rss.xml";
            $ch = curl_init($feedUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: Mozilla/5.0']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 4);
            $body = curl_exec($ch);
            curl_close($ch);

            if ($body) {
                $xml = @simplexml_load_string($body);
                if ($xml && isset($xml->channel->item)) {
                    foreach ($xml->channel->item as $item) {
                        $text = $item->title . ' ' . $item->description;
                        if (str_contains(strtolower($text), strtolower($selectedCountry->country_name))) {
                            $analysis = SentimentAnalyzer::analyze($text);
                            $matchedNews[] = [
                                'title' => (string)$item->title,
                                'description' => (string)$item->description,
                                'url' => (string)$item->link,
                                'published_at' => (string)$item->pubDate,
                                'sentiment' => $analysis['sentiment'],
                                'pos_count' => $analysis['positive_count'],
                                'neg_count' => $analysis['negative_count'],
                            ];
                        }
                    }
                    if (count($matchedNews) > 0) {
                        $texts = array_map(function($n) { return $n['title'] . ' ' . $n['description']; }, $matchedNews);
                        $sentimentStats = SentimentAnalyzer::analyzeBatch($texts);
                    }
                }
            }
        } catch (\Exception $e) {}

        return view('country-dashboard.index', [
            'countries' => $countries,
            'selectedCountry' => $selectedCountry,
            'economy' => $economy,
            'weather' => $weather,
            'weatherError' => $weatherError,
            'exchangeRate' => $exchangeRate,
            'matchedNews' => array_slice($matchedNews, 0, 5),
            'sentimentStats' => $sentimentStats,
        ]);
    }
}
