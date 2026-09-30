<?php

namespace App\Livewire;

use App\Models\AdApplication;
use App\Models\Setting;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminBiddingSettings extends Component
{
    public $topBidMode = 'auto'; // 'auto' or 'manual'

    public $selectedMerchants = []; // array of user IDs

    public $availableMerchants = [];

    public $adApplications = [];

    // For rejection modal/input
    public $rejectingAppId = null;

    public $rejectReason = '';

    public function mount()
    {
        // Load Settings
        $modeSetting = Setting::where('key', 'top_bid_mode')->first();
        if ($modeSetting) {
            $this->topBidMode = $modeSetting->value;
        }

        $merchantsSetting = Setting::where('key', 'top_bid_merchants')->first();
        if ($merchantsSetting && $merchantsSetting->value) {
            $this->selectedMerchants = json_decode($merchantsSetting->value, true) ?? [];
        }

        $this->availableMerchants = User::role('merchant')->where('is_subscribed', true)->get();

        $this->loadAdApplications();
    }

    public function loadAdApplications()
    {
        $this->adApplications = AdApplication::with('merchant')->latest()->get();
    }

    public function saveSettings()
    {
        if ($this->topBidMode === 'manual' && count($this->selectedMerchants) > 3) {
            session()->flash('error', 'Maksimal hanya 3 merchant yang dapat dipilih.');

            return;
        }

        Setting::updateOrCreate(
            ['key' => 'top_bid_mode'],
            ['value' => $this->topBidMode]
        );

        Setting::updateOrCreate(
            ['key' => 'top_bid_merchants'],
            ['value' => json_encode($this->selectedMerchants)]
        );

        session()->flash('message', 'Pengaturan Top Bid berhasil disimpan.');
    }

    public function approveAd($id)
    {
        $app = AdApplication::findOrFail($id);

        // Optionally reject others if we only allow 1 active ad at a time
        // AdApplication::where('id', '!=', $id)->where('status', 'approved')->update(['status' => 'pending']);

        $app->update(['status' => 'approved', 'admin_notes' => null]);

        $this->loadAdApplications();
        session()->flash('message', 'Iklan berhasil disetujui.');
    }

    public function confirmRejectAd($id)
    {
        $this->rejectingAppId = $id;
        $this->rejectReason = '';
    }

    public function rejectAd()
    {
        $this->validate([
            'rejectReason' => 'required|string|min:3',
        ]);

        if ($this->rejectingAppId) {
            $app = AdApplication::findOrFail($this->rejectingAppId);
            $app->update([
                'status' => 'rejected',
                'admin_notes' => $this->rejectReason,
            ]);

            $this->rejectingAppId = null;
            $this->rejectReason = '';

            $this->loadAdApplications();
            session()->flash('message', 'Iklan berhasil ditolak.');
        }
    }

    public function cancelReject()
    {
        $this->rejectingAppId = null;
        $this->rejectReason = '';
    }

    public function render()
    {
        return view('livewire.admin-bidding-settings');
    }
}
