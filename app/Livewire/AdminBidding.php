<?php

namespace App\Livewire;

use App\Models\Bid;
use App\Models\Load;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminBidding extends Component
{
    use WithPagination;

    public $editingLoadId = null;

    public $total_weight;

    public $available_weight;

    public function editLoadWeight($loadId)
    {
        $load = Load::findOrFail($loadId);
        $this->editingLoadId = $load->id;
        $this->total_weight = $load->total_weight;
        $this->available_weight = $load->available_weight;
    }

    public function saveLoadWeight()
    {
        $this->validate([
            'total_weight' => 'required|numeric|min:0.1',
            'available_weight' => 'required|numeric|min:0.1|lte:total_weight',
        ]);

        $load = Load::findOrFail($this->editingLoadId);
        $load->update([
            'total_weight' => $this->total_weight,
            'available_weight' => $this->available_weight,
        ]);

        $this->editingLoadId = null;
        $this->reset(['total_weight', 'available_weight']);
        session()->flash('message', 'Berat muatan berhasil diperbarui.');
    }

    public function cancelEdit()
    {
        $this->editingLoadId = null;
        $this->reset(['total_weight', 'available_weight']);
    }

    public function updateEscrowStatus($loadId, $status)
    {
        $load = Load::findOrFail($loadId);
        $load->update(['escrow_status' => $status]);

        // If released, find related notification and mark as read
        if ($status === 'released') {
            $acceptedBid = $load->bids->firstWhere('status', 'accepted');
            if ($acceptedBid && $acceptedBid->trip) {
                auth()->user()->unreadNotifications
                    ->where('data.trip_id', $acceptedBid->trip->id)
                    ->markAsRead();
            }
        }

        session()->flash('message', "Status pembayaran (escrow) muatan {$load->title} berhasil diubah menjadi {$status}.");
    }

    public $search = '';

    public function render()
    {
        // Get all loads that have an accepted bid, to manage escrow
        // Or get all loads generally
        $query = Load::with(['merchant', 'bids' => function ($q) {
            $q->where('status', 'accepted')->with('driver');
        }]);

        if (! empty($this->search)) {
            $query->whereHas('merchant', function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%');
            });
        }

        $loads = $query->latest()->paginate(10);
        $notifications = auth()->user()->unreadNotifications()->where('type', 'App\Notifications\TripCompletedEscrowNotification')->get();

        return view('livewire.admin-bidding', [
            'loads' => $loads,
            'notifications' => $notifications,
        ]);
    }
}
