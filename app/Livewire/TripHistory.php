<?php

namespace App\Livewire;

use App\Models\Rating;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TripHistory extends Component
{
    public $selectedTripId = null;

    public $selectedEvents = [];

    public $selectedPhotos = [];

    public $showPhotoModal = false;

    public $showRatingModal = false;

    public $ratingScore = 5;

    public $ratingReview = '';

    public $ratingTargetId = null;

    public $search = '';

    public function viewEvents($tripId)
    {
        $this->selectedTripId = $tripId;
        $trip = Trip::with('events')->find($tripId);
        $this->selectedEvents = $trip ? $trip->events : [];
    }

    public function viewPhotos($tripId)
    {
        $this->selectedTripId = $tripId;
        $trip = Trip::with('photos')->find($tripId);
        $this->selectedPhotos = $trip ? $trip->photos : [];
        $this->showPhotoModal = true;
    }

    public function closeModal()
    {
        $this->selectedTripId = null;
        $this->selectedEvents = [];
        $this->selectedPhotos = [];
        $this->showPhotoModal = false;
        $this->showRatingModal = false;
        $this->ratingTargetId = null;
        $this->ratingScore = 5;
        $this->ratingReview = '';
    }

    public function openRatingModal($tripId, $targetId)
    {
        if (! Auth::user()->is_subscribed) {
            session()->flash('error', 'Fitur Penilaian (Rating) hanya tersedia untuk Member Resmi (telah deposit).');

            return;
        }

        // Check if already rated
        $existing = Rating::where('trip_id', $tripId)->where('rater_id', Auth::id())->first();
        if ($existing) {
            session()->flash('error', 'Anda sudah memberikan penilaian untuk trip ini.');

            return;
        }

        $this->selectedTripId = $tripId;
        $this->ratingTargetId = $targetId;
        $this->showRatingModal = true;
    }

    public function submitRating()
    {
        $this->validate([
            'ratingScore' => 'required|integer|min:1|max:5',
            'ratingReview' => 'nullable|string|max:1000',
        ]);

        Rating::create([
            'trip_id' => $this->selectedTripId,
            'rater_id' => Auth::id(),
            'ratee_id' => $this->ratingTargetId,
            'score' => $this->ratingScore,
            'review' => $this->ratingReview,
        ]);

        $this->closeModal();
        session()->flash('message', 'Penilaian berhasil disimpan. Terima kasih!');
    }

    public function render()
    {
        $user = Auth::user();
        $query = Trip::with(['cargo', 'driver', 'events', 'photos'])->where('status', 'completed')->orderBy('updated_at', 'desc');

        if ($user->hasRole('administrator')) {
            // Admin sees all, no restriction
        } elseif ($user->hasRole('merchant')) {
            $query->whereHas('cargo', function ($q) use ($user) {
                $q->where('merchant_id', $user->id);
            });
        } elseif ($user->hasRole('driver')) {
            $query->where('driver_id', $user->id);
        } else {
            // Fallback if role is messed up but somehow they have access
            $query->where('driver_id', $user->id);
        }

        if (! empty($this->search)) {
            $query->where(function ($subQuery) {
                $subQuery->whereHas('cargo', function ($q) {
                    $q->whereHas('merchant', function ($q2) {
                        $q2->where('name', 'like', '%'.$this->search.'%');
                    });
                })->orWhereHas('driver', function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%');
                });
            });
        }

        $trips = $query->get();

        return view('livewire.trip-history', [
            'trips' => $trips,
        ]);
    }
}
