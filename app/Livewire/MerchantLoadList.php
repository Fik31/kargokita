<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Load;
use App\Models\Bid;
use Illuminate\Support\Facades\Auth;

class MerchantLoadList extends Component
{
    public $search = '';
    public $activeTab = 'open'; // open, waiting, completed/in_transit

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function approveBid($bidId)
    {
        $bid = Bid::findOrFail($bidId);
        $load = $bid->cargo;

        if ($load->merchant_id !== Auth::id()) {
            abort(403);
        }

        Bid::where('load_id', $load->id)->where('id', '!=', $bid->id)->update(['status' => 'rejected']);

        $bid->update(['status' => 'accepted']);
        $load->update(['status' => 'in_transit']);

        session()->flash('message', 'Bid berhasil disetujui! Supir segera menuju lokasi.');
    }

    public function repostLoad($loadId)
    {
        $oldLoad = Load::findOrFail($loadId);
        
        if ($oldLoad->merchant_id !== Auth::id()) {
            abort(403);
        }

        session()->put('repost_load_id', $oldLoad->id);
        return redirect()->route('bidding');
    }

    public function render()
    {
        $user = Auth::user();

        $query = Load::with(['merchant', 'bids.driver'])->where('merchant_id', $user->id);

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('item_name', 'like', '%' . $this->search . '%')
                  ->orWhere('type', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->activeTab === 'open') {
            $query->where('status', 'open')->doesntHave('bids');
        } elseif ($this->activeTab === 'waiting') {
            $query->where('status', 'open')->has('bids');
        } else {
            $query->whereIn('status', ['in_transit', 'completed', 'delivered', 'closed']);
        }

        $loads = $query->latest()->get();

        return view('livewire.merchant-load-list', [
            'loads' => $loads
        ])->layout('layouts.app');
    }
}
