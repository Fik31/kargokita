<?php

namespace App\Models;

use App\Enums\ComplianceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssessmentItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'status' => ComplianceStatus::class,
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function criterion()
    {
        return $this->belongsTo(AssessmentCriterion::class, 'criterion_id');
    }

    public function correctiveAction()
    {
        return $this->hasOne(CorrectiveAction::class);
    }
}
