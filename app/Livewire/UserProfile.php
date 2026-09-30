<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Load;
use Livewire\Component;

class UserProfile extends Component
{
    public User $user;
    public $activeBids = [];

    public function mount(User $user)
    {
        $this->user = $user;
        
        // If merchant, load active bids
        if ($this->user->hasRole('merchant')) {
            $this->activeBids = Load::where('merchant_id', $this->user->id)
                ->where('status', 'pending')
                ->latest()
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.user-profile')->layout('layouts.app');
    }
}
