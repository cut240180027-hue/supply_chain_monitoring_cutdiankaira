<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_code',
        'supplier_id',
        'origin_country_id',
        'destination_country_id',
        'origin_port_id',
        'destination_port_id',
        'vessel_name',
        'departure_date',
        'estimated_arrival',
        'status',
        'risk_level',
        'latitude',
        'longitude',
        'description',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function originCountry()
    {
        return $this->belongsTo(Country::class, 'origin_country_id');
    }

    public function destinationCountry()
    {
        return $this->belongsTo(Country::class, 'destination_country_id');
    }

    public function originPort()
    {
        return $this->belongsTo(Port::class, 'origin_port_id');
    }

    public function destinationPort()
    {
        return $this->belongsTo(Port::class, 'destination_port_id');
    }

    public function exchangeRates()
    {
        return $this->hasMany(ExchangeRate::class);
    }

    public function weatherLogs()
    {
        return $this->hasMany(WeatherLog::class);
    }

    public function riskScores()
    {
        return $this->hasMany(RiskScore::class);
    }

    public function riskScore()
    {
        return $this->hasOne(RiskScore::class)->latestOfMany();
    }

    public function newsLogs()
    {
        return $this->hasMany(NewsLog::class);
    }

    public function recalculateRiskScore()
    {
        // 1. Weather Score (30%)
        $weatherScore = 20; // Default
        try {
            if ($this->latitude && $this->longitude) {
                $response = \Illuminate\Support\Facades\Http::timeout(5)->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => $this->latitude,
                    'longitude' => $this->longitude,
                    'current' => 'wind_speed_10m,precipitation',
                    'timezone' => 'auto'
                ]);
                if ($response->successful()) {
                    $wData = $response->json();
                    $wind = $wData['current']['wind_speed_10m'] ?? 0;
                    $precip = $wData['current']['precipitation'] ?? 0;
                    $weatherScore = max(10, min(100, ($wind * 1.5) + ($precip * 3.5)));
                }
            }
        } catch (\Exception $e) {}

        // 2. Currency Score (25%)
        $currencyScore = 20; // Default
        try {
            $indicator = \App\Models\EconomicIndicator::where('country_id', $this->destination_country_id)
                ->orderBy('year', 'desc')
                ->first();
            if ($indicator && $indicator->inflation) {
                if ($indicator->inflation > 10) {
                    $currencyScore = 85;
                } elseif ($indicator->inflation > 5) {
                    $currencyScore = 55;
                } else {
                    $currencyScore = 25;
                }
            }
        } catch (\Exception $e) {}

        // 3. Port Score (20%)
        $activeShipmentsCount = Shipment::where('destination_port_id', $this->destination_port_id)
            ->where('status', '!=', 'Delivered')
            ->count();
        $portScore = max(15, min(100, 20 + ($activeShipmentsCount * 15)));

        // 4. Geopolitical Score (25%)
        $geopoliticalScore = 20; // Default
        try {
            $destCountry = $this->destinationCountry;
            $queryUrl = "https://feeds.bbci.co.uk/news/world/rss.xml";
            $ch = curl_init($queryUrl);
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
                        if ($destCountry && str_contains(strtolower($text), strtolower($destCountry->country_name))) {
                            $matchedTexts[] = $text;
                        }
                    }
                    if (count($matchedTexts) > 0) {
                        $batchStats = \App\Services\SentimentAnalyzer::analyzeBatch($matchedTexts);
                        $geopoliticalScore = 20 + ($batchStats['Negative'] * 0.8) + ($batchStats['Neutral'] * 0.3);
                        $geopoliticalScore = max(20, min(100, round($geopoliticalScore)));
                    }
                }
            }
        } catch (\Exception $e) {}

        // Hitung total skor berdasarkan weightage
        $totalScore = ($weatherScore * 0.30) + ($currencyScore * 0.25) + ($portScore * 0.20) + ($geopoliticalScore * 0.25);
        $totalScore = round($totalScore);

        if ($totalScore < 40) {
            $riskLevel = 'Low';
        } elseif ($totalScore < 70) {
            $riskLevel = 'Medium';
        } else {
            $riskLevel = 'High';
        }

        // Simpan ke risk_scores
        \App\Models\RiskScore::updateOrCreate(
            ['shipment_id' => $this->id],
            [
                'weather_score' => (int)$weatherScore,
                'currency_score' => (int)$currencyScore,
                'port_score' => (int)$portScore,
                'geopolitical_score' => (int)$geopoliticalScore,
                'economic_score' => (int)$currencyScore,
                'total_score' => (int)$totalScore,
                'risk_level' => $riskLevel,
            ]
        );

        // Update shipment's risk level
        $this->risk_level = $riskLevel;

        // Dynamic Delay Alerts & ETA Adjustment Logic
        if (in_array($this->status, ['Pending', 'On Shipping'])) {
            $hasDelay = false;
            $delayDays = 0;
            $reasons = [];

            if ($weatherScore >= 60) {
                $hasDelay = true;
                $delayDays += 3;
                $reasons[] = "Cuaca Buruk (Skor: {$weatherScore})";
                
                // Log weather notification if not exists
                $exists = \App\Models\Notification::where('shipment_id', $this->id)
                    ->where('type', 'weather')
                    ->where('created_at', '>=', now()->subHours(12))
                    ->exists();
                if (!$exists) {
                    \App\Models\Notification::create([
                        'shipment_id' => $this->id,
                        'title' => "🌧️ Gangguan Cuaca — {$this->shipment_code}",
                        'message' => "Kondisi cuaca buruk terdeteksi di rute pelayaran. Kecepatan angin/curah hujan tinggi terdeteksi.",
                        'type' => 'weather',
                    ]);
                }
            }

            if ($portScore >= 60) {
                $hasDelay = true;
                $delayDays += 2;
                $reasons[] = "Kepadatan Pelabuhan (Skor: {$portScore})";

                // Log port notification if not exists
                $exists = \App\Models\Notification::where('shipment_id', $this->id)
                    ->where('type', 'port')
                    ->where('created_at', '>=', now()->subHours(12))
                    ->exists();
                if (!$exists) {
                    \App\Models\Notification::create([
                        'shipment_id' => $this->id,
                        'title' => "⚓ Kepadatan Pelabuhan — {$this->shipment_code}",
                        'message' => "Antrean kapal tinggi terdeteksi di pelabuhan tujuan, meningkatkan potensi keterlambatan sandar.",
                        'type' => 'port',
                    ]);
                }
            }

            if ($geopoliticalScore >= 60) {
                $hasDelay = true;
                $delayDays += 4;
                $reasons[] = "Risiko Geopolitik (Skor: {$geopoliticalScore})";

                // Log geopolitical notification if not exists
                $exists = \App\Models\Notification::where('shipment_id', $this->id)
                    ->where('type', 'geopolitical')
                    ->where('created_at', '>=', now()->subHours(12))
                    ->exists();
                if (!$exists) {
                    \App\Models\Notification::create([
                        'shipment_id' => $this->id,
                        'title' => "🛡️ Konflik Geopolitik — {$this->shipment_code}",
                        'message' => "Ketegangan geopolitik/demonstrasi terdeteksi di dekat pelabuhan transit/tujuan.",
                        'type' => 'geopolitical',
                    ]);
                }
            }

            if ($hasDelay) {
                $oldStatus = $this->status;
                $this->status = 'Delayed';

                if ($this->estimated_arrival) {
                    $oldEta = \Carbon\Carbon::parse($this->estimated_arrival);
                    $newEta = $oldEta->addDays($delayDays);
                    $this->estimated_arrival = $newEta->format('Y-m-d');

                    // Log delay alert notification
                    \App\Models\Notification::create([
                        'shipment_id' => $this->id,
                        'title' => "🚨 Penyesuaian ETA — {$this->shipment_code}",
                        'message' => "ETA mundur {$delayDays} hari karena " . implode(', ', $reasons) . ". ETA disesuaikan ke " . $newEta->format('d M Y') . ".",
                        'type' => 'delay',
                    ]);
                }
            }
        }

        $this->save();

        return $totalScore;
    }
}