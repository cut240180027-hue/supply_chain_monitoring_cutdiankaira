<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'port_name',
        'latitude',
        'longitude'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
