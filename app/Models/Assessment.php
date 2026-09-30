<?php

namespace App\Models;

use App\Enums\AssessmentLevel;
use App\Enums\AssessmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'total_score' => 'decimal:2',
        'has_mandatory_failure' => 'boolean',
        'status' => AssessmentStatus::class,
        'level' => AssessmentLevel::class,
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }

    public function items()
    {
        return $this->hasMany(AssessmentItem::class);
    }
}
