<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class CommonInstantOrder extends Component
{
    use WithFileUploads;

    public $ktp_photo;
    public $selfie_photo;
    public $isVerified = false;

    // Form fields
    public $sender_address;
    public $receiver_address;
    public $max_price;
    public $title;
    public $item_name;

    public function mount()
    {
        $this->isVerified = Auth::user()->is_common_verified;
    }

    public function verifyAccount()
    {
        $this->validate([
            'ktp_photo' => 'nullable|image|max:2048',
            'selfie_photo' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();

        if ($this->ktp_photo) {
            $user->ktp_photo_path = $this->ktp_photo->store('kyc/ktp', 'public');
        }
        if ($this->selfie_photo) {
            $user->selfie_photo_path = $this->selfie_photo->store('kyc/selfie', 'public');
        }

        $user->is_common_verified = true;
        $user->save();
        $this->isVerified = true;

        session()->flash('message', 'Verifikasi berhasil! Anda sekarang dapat membuat Quick Bid.');
    }

    public function getActiveLoadProperty()
    {
        return \App\Models\Load::with(['trip.driver'])
            ->where('merchant_id', Auth::id())
            ->where('is_instant', true)
            ->whereIn('status', ['open', 'in_transit'])
            ->latest()
            ->first();
    }

    public function checkStatus()
    {
        $load = $this->activeLoad;
        
        // MOCK FOR PRESENTATION: Automatically assign a driver after a few seconds
        if ($load && $load->status === 'open') {
            // Find a random driver
            $driver = \App\Models\User::role('driver')->first();
            
            if ($driver) {
                // Fake creating a trip
                $trip = \App\Models\Trip::create([
                    'load_id' => $load->id,
                    'driver_id' => $driver->id,
                    'status' => 'accepted',
                ]);

                $load->status = 'in_transit';
                $load->save();
            }
        }
    }

    public function createNewOrder()
    {
        $load = $this->activeLoad;
        if ($load) {
            $load->status = 'closed'; // or completed
            $load->save();
        }
    }

    public function submitOrder()
    {
        $this->validate([
            'sender_address' => 'required|string|max:255',
            'receiver_address' => 'required|string|max:255',
            'max_price' => 'required|numeric|min:1000',
            'title' => 'required|string|max:255',
            'item_name' => 'required|string|max:255',
        ]);

        \App\Models\Load::create([
            'merchant_id' => Auth::id(),
            'is_instant' => true,
            'status' => 'open',
            'type' => 'FTL',
            'max_price' => $this->max_price,
            'sender_address' => $this->sender_address,
            'receiver_address' => $this->receiver_address,
            'title' => $this->title,
            'item_name' => $this->item_name,
        ]);

        // Reset form
        $this->reset(['sender_address', 'receiver_address', 'max_price', 'title', 'item_name']);
        session()->flash('message', 'Quick Bid berhasil diposting! Menunggu driver...');
    }

    public function cancelOrder()
    {
        $load = $this->activeLoad;
        if ($load && $load->status === 'open') {
            $load->status = 'closed';
            $load->save();
        }
    }

    public function render()
    {
        return view('livewire.common-instant-order')->layout('layouts.app');
    }
}
