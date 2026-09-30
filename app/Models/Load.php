<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Load extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id', 'type', 'total_weight', 'available_weight',
        'max_price', 'status', 'origin_lat', 'origin_lng',
        'dest_lat', 'dest_lng', 'route_polyline', 'min_driver_tier',
        'title', 'item_name', 'weight_kg', 'koli', 'vehicle_type_needed',
        'sender_name', 'sender_phone', 'sender_address',
        'receiver_name', 'receiver_phone', 'receiver_address',
        'distance', 'bid_deadline', 'escrow_status', 'is_paylater',
        'sender_notes', 'receiver_notes', 'is_urgent',
    ];

    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function trip()
    {
        return $this->hasOne(Trip::class);
    }
}
