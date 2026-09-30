<?php

namespace App\Models;

use App\Enums\CaStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectiveAction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'due_date' => 'date',
        'status' => CaStatus::class,
    ];

    public function assessmentItem()
    {
        return $this->belongsTo(AssessmentItem::class);
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }
}
