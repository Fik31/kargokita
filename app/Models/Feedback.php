<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'trip_id',
        'reviewer_id',
        'reviewee_id',
        'role',
        'partner_rating',
        'partner_feedback',
        'app_rating',
        'app_feedback',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }
}
