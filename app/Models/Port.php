<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'port_name',
        'latitude',
        'longitude',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function originShipments()
    {
        return $this->hasMany(Shipment::class, 'origin_port_id');
    }

    public function destinationShipments()
    {
        return $this->hasMany(Shipment::class, 'destination_port_id');
    }
}