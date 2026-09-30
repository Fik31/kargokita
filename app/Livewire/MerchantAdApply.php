<?php

namespace App\Livewire;

use App\Models\AdApplication;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class MerchantAdApply extends Component
{
    public $activeApplication = null;

    public function mount()
    {
        $this->activeApplication = AdApplication::where('merchant_id', Auth::id())
            ->latest()
            ->first();
    }

    public function applyForAd()
    {
        $hasActive = AdApplication::where('merchant_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($hasActive) {
            session()->flash('error', 'Anda sudah memiliki pengajuan iklan yang aktif atau sedang diproses.');

            return;
        }

        AdApplication::create([
            'merchant_id' => Auth::id(),
            'status' => 'pending',
        ]);

        $this->activeApplication = AdApplication::where('merchant_id', Auth::id())->latest()->first();

        session()->flash('message', 'Pengajuan iklan berhasil dikirim! Menunggu persetujuan Administrator.');
    }

    public function render()
    {
        return view('livewire.merchant-ad-apply');
    }
}
