<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    protected $fillable = [
        'trip_id',
        'load_id',
        'reporter_id',
        'type',
        'reason',
        'status',
        'resolution_notes',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function cargoLoad()
    {
        return $this->belongsTo(Load::class, 'load_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }
}
