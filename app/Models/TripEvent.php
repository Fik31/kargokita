<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripEvent extends Model
{
    protected $fillable = ['trip_id', 'type', 'location_name', 'duration_minutes', 'lat', 'lng', 'notes'];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
