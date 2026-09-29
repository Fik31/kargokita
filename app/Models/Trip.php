<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['load_id', 'driver_id', 'status', 'current_lat', 'current_lng'];

    public function cargo()
    {
        return $this->belongsTo(Load::class, 'load_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function photos()
    {
        return $this->hasMany(TripPhoto::class);
    }

    public function events()
    {
        return $this->hasMany(TripEvent::class);
    }

    public function waybill()
    {
        return $this->hasOne(Waybill::class);
    }
}
