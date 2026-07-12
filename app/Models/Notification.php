<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'title',
        'message',
        'type',
        'is_read',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}
