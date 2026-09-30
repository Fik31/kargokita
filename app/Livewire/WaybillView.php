<?php

namespace App\Livewire;

use App\Models\Feedback;
use App\Models\Trip;
use App\Models\Waybill;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WaybillView extends Component
{
    public $trip_id;

    public $trip;

    public $waybill;

    public $driver_signature_data;

    public $merchant_signature_data;

    // Feedback
    public $partner_rating;

    public $partner_feedback;

    public $app_rating;

    public $app_feedback;

    public $has_submitted_feedback = false;

    public function mount($trip_id)
    {
        $this->trip_id = $trip_id;
        $this->trip = Trip::with(['cargo.merchant', 'driver'])->findOrFail($trip_id);
        $this->waybill = Waybill::firstOrCreate(['trip_id' => $trip_id]);

        $this->has_submitted_feedback = Feedback::where('trip_id', $this->trip_id)
            ->where('reviewer_id', Auth::id())
            ->exists();
    }

    public function saveDriverSignature()
    {
        if ($this->driver_signature_data) {
            $this->waybill->update([
                'driver_signature' => $this->driver_signature_data,
                'status' => $this->waybill->merchant_signature ? 'completed' : 'driver_signed',
            ]);
            session()->flash('message', 'Tanda tangan Driver berhasil disimpan.');
        }
    }

    public function saveMerchantSignature()
    {
        if ($this->merchant_signature_data) {
            $this->waybill->update([
                'merchant_signature' => $this->merchant_signature_data,
                'status' => $this->waybill->driver_signature ? 'completed' : 'merchant_signed',
            ]);
            session()->flash('message', 'Tanda tangan Merchant berhasil disimpan.');
        }
    }

    public function submitFeedback()
    {
        $this->validate([
            'partner_rating' => 'required|integer|min:1|max:5',
            'partner_feedback' => 'nullable|string',
            'app_rating' => 'required|integer|min:1|max:5',
            'app_feedback' => 'nullable|string',
        ]);

        $user = Auth::user();
        $isDriver = $user->hasRole('driver');
        $role = $isDriver ? 'driver' : 'merchant';
        $reviewee_id = $isDriver ? $this->trip->cargo->merchant_id : $this->trip->driver_id;

        Feedback::create([
            'trip_id' => $this->trip_id,
            'reviewer_id' => $user->id,
            'reviewee_id' => $reviewee_id,
            'role' => $role,
            'partner_rating' => $this->partner_rating,
            'partner_feedback' => $this->partner_feedback,
            'app_rating' => $this->app_rating,
            'app_feedback' => $this->app_feedback,
        ]);

        $this->has_submitted_feedback = true;
        session()->flash('message', 'Terima kasih atas feedback Anda!');
    }

    public function render()
    {
        return view('livewire.waybill-view')->layout('layouts.app');
    }
}
