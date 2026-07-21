<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\EconomicIndicator;
use App\Services\SentimentAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CountryComparisonController extends Controller
{
    public function index(Request $request)
    {
        $countries = Country::orderBy('country_name')->get();

        if ($countries->isEmpty()) {
            return view('comparison.index', [
                'countries' => collect(),
                'countryA' => null,
                'countryB' => null,
                'error' => 'Belum ada data negara di database.',
            ]);
        }

        // Get selected countries, default to first two
        $codeA = strtoupper($request->get('country_a', $countries[0]->country_code ?? ''));
        $codeB = strtoupper($request->get('country_b', $countries[1]->country_code ?? $countries[0]->country_code ?? ''));

        $countryA = $countries->firstWhere('country_code', $codeA) ?? $countries[0];
        $countryB = $countries->firstWhere('country_code', $codeB) ?? $countries[1] ?? $countries[0];

        $dataA = $this->getCountryCompareData($countryA);
        $dataB = $this->getCountryCompareData($countryB);

        return view('comparison.index', [
            'countries' => $countries,
            'countryA' => $countryA,
            'countryB' => $countryB,
            'dataA' => $dataA,
            'dataB' => $dataB,
        ]);
    }

    protected function getCountryCompareData(Country $country)
    {
        // 1. Economy
        $economy = EconomicIndicator::where('country_id', $country->id)
            ->orderBy('year', 'desc')
            ->first();

        // 2. Weather
        $weather = null;
        if ($country->latitude && $country->longitude) {
            try {
                $response = Http::timeout(3)->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => $country->latitude,
                    'longitude' => $country->longitude,
                    'current' => 'temperature_2m,weather_code,wind_speed_10m,precipitation',
                    'timezone' => 'auto'
                ]);
                if ($response->successful()) {
                    $weather = $response->json('current');
                }
            } catch (\Exception $e) {}
        }

        // 3. Currency Rate
        $exchangeRate = 1.0;
        if ($country->currency_code && $country->currency_code !== '-') {
            try {
                $rateResponse = Http::timeout(3)->get('https://open-er-api.com/v6/latest/USD');
                if ($rateResponse->successful()) {
                    $rates = $rateResponse->json('rates');
                    $exchangeRate = $rates[$country->currency_code] ?? null;
                }
            } catch (\Exception $e) {}
        }

        // 4. News Sentiment & Risk Estimation
        $sentimentStats = ['Positive' => 0, 'Neutral' => 100, 'Negative' => 0, 'total' => 0];
        $riskScore = 20; // default low risk
        try {
            $feedUrl = "https://feeds.bbci.co.uk/news/world/rss.xml";
            $ch = curl_init($feedUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: Mozilla/5.0']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $body = curl_exec($ch);
            curl_close($ch);

            if ($body) {
                $xml = @simplexml_load_string($body);
                if ($xml && isset($xml->channel->item)) {
                    $matchedTexts = [];
                    foreach ($xml->channel->item as $item) {
                        $text = $item->title . ' ' . $item->description;
                        if (str_contains(strtolower($text), strtolower($country->country_name))) {
                            $matchedTexts[] = $text;
                        }
                    }
                    if (count($matchedTexts) > 0) {
                        $sentimentStats = SentimentAnalyzer::analyzeBatch($matchedTexts);
                    }
                }
            }
        } catch (\Exception $e) {}

        // Calculate a simple overall risk estimate based on weather + inflation + currency + sentiment
        $wScore = ($weather && ($weather['wind_speed_10m'] > 30 || $weather['precipitation'] > 5)) ? 75 : 30;
        $iScore = ($economy && $economy->inflation > 8) ? 80 : 35;
        $cScore = ($exchangeRate && $exchangeRate > 1000) ? 50 : 25;
        $sScore = 20 + ($sentimentStats['Negative'] * 0.8) + ($sentimentStats['Neutral'] * 0.2);
        
        $riskScore = round(($wScore * 0.3) + ($iScore * 0.25) + ($cScore * 0.2) + ($sScore * 0.25));

        return [
            'economy' => $economy,
            'weather' => $weather,
            'exchangeRate' => $exchangeRate,
            'sentimentStats' => $sentimentStats,
            'riskScore' => $riskScore,
        ];
    }
}
