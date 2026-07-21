<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PositiveWord;
use App\Models\NegativeWord;

class LexiconSeeder extends Seeder
{
    public function run(): void
    {
        $positives = [
            'growth', 'increase', 'profit', 'stable', 'improve', 
            'success', 'rise', 'gain', 'recovery', 'boost', 
            'up', 'positive', 'strong', 'expansion', 'surplus'
        ];

        $negatives = [
            'war', 'crisis', 'inflation', 'delay', 'disaster', 
            'conflict', 'decline', 'drop', 'tension', 'shortage', 
            'strike', 'dispute', 'sanctions', 'tariff', 'loss', 
            'collapse', 'danger', 'protest', 'disruption', 'strike'
        ];

        foreach ($positives as $word) {
            PositiveWord::updateOrCreate(['word' => $word]);
        }

        foreach ($negatives as $word) {
            NegativeWord::updateOrCreate(['word' => $word]);
        }
    }
}
