<?php

namespace App\Services;

use App\Models\PositiveWord;
use App\Models\NegativeWord;

class SentimentAnalyzer
{
    protected static $positives = null;
    protected static $negatives = null;

    protected static function loadDictionary()
    {
        if (self::$positives === null) {
            self::$positives = PositiveWord::pluck('word')->toArray();
        }
        if (self::$negatives === null) {
            self::$negatives = NegativeWord::pluck('word')->toArray();
        }
    }

    /**
     * Analyze sentiment of a given text using Lexicon-based matching.
     *
     * @param string $text
     * @return array
     */
    public static function analyze(string $text): array
    {
        self::loadDictionary();

        // Standardize text: lowercase and strip punctuation
        $cleanText = strtolower(preg_replace('/[^a-z0-9\s]/', '', $text));
        $words = preg_split('/\s+/', $cleanText, -1, PREG_SPLIT_NO_EMPTY);

        $posCount = 0;
        $negCount = 0;
        $matchedPositives = [];
        $matchedNegatives = [];

        foreach ($words as $word) {
            if (in_array($word, self::$positives)) {
                $posCount++;
                $matchedPositives[] = $word;
            }
            if (in_array($word, self::$negatives)) {
                $negCount++;
                $matchedNegatives[] = $word;
            }
        }

        $sentiment = 'Neutral';
        if ($posCount > $negCount) {
            $sentiment = 'Positive';
        } elseif ($negCount > $posCount) {
            $sentiment = 'Negative';
        }

        return [
            'positive_count' => $posCount,
            'negative_count' => $negCount,
            'sentiment' => $sentiment,
            'matched_positives' => array_unique($matchedPositives),
            'matched_negatives' => array_unique($matchedNegatives),
        ];
    }

    /**
     * Analyze batch of texts and get statistical distribution.
     *
     * @param array $texts Array of strings
     * @return array
     */
    public static function analyzeBatch(array $texts): array
    {
        $total = count($texts);
        if ($total === 0) {
            return [
                'Positive' => 0,
                'Neutral' => 100,
                'Negative' => 0,
                'total' => 0
            ];
        }

        $pos = 0;
        $neg = 0;
        $neu = 0;

        foreach ($texts as $text) {
            $result = self::analyze($text);
            if ($result['sentiment'] === 'Positive') {
                $pos++;
            } elseif ($result['sentiment'] === 'Negative') {
                $neg++;
            } else {
                $neu++;
            }
        }

        return [
            'Positive' => round(($pos / $total) * 100),
            'Neutral' => round(($neu / $total) * 100),
            'Negative' => round(($neg / $total) * 100),
            'total' => $total
        ];
    }
}
