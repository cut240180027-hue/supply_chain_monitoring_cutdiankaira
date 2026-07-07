<?php

namespace App\Models;

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
        'longitude'
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
}