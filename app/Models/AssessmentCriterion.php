<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssessmentCriterion extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_mandatory' => 'boolean',
        'weight' => 'decimal:2',
    ];
}
