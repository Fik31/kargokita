<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['trip_id', 'type', 'path'];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
