<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'shipment_id',
        'title',
        'source',
        'risk_level',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}