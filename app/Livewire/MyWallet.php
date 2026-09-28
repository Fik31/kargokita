<?php

namespace App\Livewire;

use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class MyWallet extends Component
{
    public $wallet;

    public function mount()
    {
        $this->wallet = Wallet::with('transactions')->firstOrCreate([
            'user_id' => Auth::id()
        ]);
    }

    public function render()
    {
        return view('livewire.my-wallet');
    }
}
