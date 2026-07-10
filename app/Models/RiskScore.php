<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'weather_score',
        'currency_score',
        'port_score',
        'geopolitical_score',
        'economic_score',
        'total_score',
        'risk_level',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}