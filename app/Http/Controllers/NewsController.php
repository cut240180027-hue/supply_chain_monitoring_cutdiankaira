<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'all');
        $apiKey = env('GNEWS_API_KEY');

        $articles = [];
        $sourceType = 'RSS Feed (Fallback)';

        if ($apiKey) {
            // Jika ada GNews API key, gunakan GNews API
            $query = 'economy OR geopolitics OR logistics OR supply chain';
            if ($category === 'economics') {
                $query = 'economy OR inflation OR finance';
            } elseif ($category === 'logistics') {
                $query = 'logistics OR shipping OR ports OR supply chain';
            } elseif ($category === 'geopolitics') {
                $query = 'geopolitics OR sanctions OR war OR conflict';
            }

            try {
                $response = Http::timeout(10)->get('https://gnews.io/api/v4/search', [
                    'q'     => $query,
                    'lang'  => 'en',
                    'token' => $apiKey,
                    'max'   => 15,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['articles'])) {
                        $sourceType = 'GNews API';
                        foreach ($data['articles'] as $item) {
                            $articles[] = [
                                'title'       => $item['title'],
                                'description' => $item['description'] ?? '',
                                'url'         => $item['url'],
                                'image'       => $item['image'] ?? null,
                                'source'      => $item['source']['name'] ?? 'GNews',
                                'published_at'=> $item['publishedAt'],
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                // Terdiam jika error, fallback ke RSS
            }
        }

        // Jika GNews tidak ada atau gagal, fallback ke RSS Feeds yang stabil & gratis
        if (empty($articles)) {
            $feeds = [];
            
            if ($category === 'all') {
                $feeds = [
                    'Global Trade' => 'https://www.globaltrademag.com/feed/',
                    'BBC Business' => 'https://feeds.bbci.co.uk/news/business/rss.xml',
                    'BBC World'    => 'https://feeds.bbci.co.uk/news/world/rss.xml',
                ];
            } elseif ($category === 'economics') {
                $feeds = [
                    'BBC Business' => 'https://feeds.bbci.co.uk/news/business/rss.xml',
                    'Yahoo Finance'=> 'https://finance.yahoo.com/news/rssindex',
                ];
            } elseif ($category === 'logistics') {
                $feeds = [
                    'Global Trade' => 'https://www.globaltrademag.com/feed/',
                ];
            } elseif ($category === 'geopolitics') {
                $feeds = [
                    'BBC World'    => 'https://feeds.bbci.co.uk/news/world/rss.xml',
                ];
            }

            foreach ($feeds as $feedName => $url) {
                try {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)']);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    
                    $body = curl_exec($ch);
                    curl_close($ch);

                    if ($body) {
                        $xml = @simplexml_load_string($body);
                        if ($xml && isset($xml->channel->item)) {
                            foreach ($xml->channel->item as $item) {
                                // Ekstrak URL gambar jika ada di enclosure/media namespace
                                $image = null;
                                if (isset($item->enclosure) && isset($item->enclosure['url'])) {
                                    $image = (string)$item->enclosure['url'];
                                }
                                
                                $articles[] = [
                                    'title'       => (string)$item->title,
                                    'description' => strip_tags((string)$item->description),
                                    'url'         => (string)$item->link,
                                    'image'       => $image,
                                    'source'      => $feedName,
                                    'published_at'=> (string)$item->pubDate,
                                ];
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Lanjutkan jika ada feed yang gagal
                }
            }

            // Sortir berita berdasarkan pubDate terbaru
            usort($articles, function ($a, $b) {
                return strtotime($b['published_at']) - strtotime($a['published_at']);
            });

            // Batasi 20 berita teratas
            $articles = array_slice($articles, 0, 20);
        }

        // Hitung Risk Level berdasarkan kata kunci judul/deskripsi
        foreach ($articles as &$art) {
            $text = strtolower($art['title'] . ' ' . $art['description']);
            
            $highKeywords   = ['strike', 'closure', 'crisis', 'war', 'attack', 'blockade', 'protest', 'disaster', 'tsunami', 'earthquake', 'storm', 'shutdown', 'bomb', 'explosion'];
            $mediumKeywords = ['delay', 'inflation', 'disruption', 'tariff', 'sanctions', 'shortage', 'tension', 'dispute', 'decline', 'drop', 'tax', 'cut'];

            $art['risk_level'] = 'Low';
            $art['risk_color'] = '#10b981'; // Green

            foreach ($highKeywords as $hk) {
                if (str_contains($text, $hk)) {
                    $art['risk_level'] = 'High';
                    $art['risk_color'] = '#ef4444'; // Red
                    break;
                }
            }

            if ($art['risk_level'] === 'Low') {
                foreach ($mediumKeywords as $mk) {
                    if (str_contains($text, $mk)) {
                        $art['risk_level'] = 'Medium';
                        $art['risk_color'] = '#f59e0b'; // Amber
                        break;
                    }
                }
            }
        }

        return view('news.index', [
            'articles'   => $articles,
            'category'   => $category,
            'sourceType' => $sourceType,
        ]);
    }
}