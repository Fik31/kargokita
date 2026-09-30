<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'tier', 'referral_code', 'is_subscribed'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function verificationRequests()
    {
        return $this->hasMany(VerificationRequest::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function loads()
    {
        return $this->hasMany(Load::class, 'merchant_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class, 'driver_id');
    }

    public function trips()
    {
        return $this->hasMany(Trip::class, 'driver_id');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function brandingApplications()
    {
        return $this->hasMany(BrandingApplication::class, 'driver_id');
    }

    public function ratingsAsRatee()
    {
        return $this->hasMany(Rating::class, 'ratee_id');
    }

    public function galleries()
    {
        return $this->hasMany(UserGallery::class);
    }

    public function getSuccessfulDeliveryPercentageAttribute()
    {
        $total = $this->trips_count ?? $this->trips()->count();
        if ($total == 0) return 0;

        $completed = $this->completed_trips_count ?? $this->trips()->where('status', 'completed')->count();
        return round(($completed / $total) * 100);
    }
}
