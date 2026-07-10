<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_code',
        'country_name',
        'currency',
        'currency_code',
        'capital',
        'region',
        'subregion',
        'timezone',
        'language',
        'latitude',
        'longitude',
    ];

    public function ports()
    {
        return $this->hasMany(Port::class);
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    public function economicIndicators()
    {
        return $this->hasMany(EconomicIndicator::class);
    }

    public function newsLogs()
    {
        return $this->hasMany(NewsLog::class);
    }

    public function originShipments()
    {
        return $this->hasMany(Shipment::class, 'origin_country_id');
    }

    public function destinationShipments()
    {
        return $this->hasMany(Shipment::class, 'destination_country_id');
    }
}