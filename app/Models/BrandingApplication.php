<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrandingApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'status',
        'proof_image',
        'admin_notes',
        'applied_at',
        'deadline',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'deadline' => 'datetime',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
