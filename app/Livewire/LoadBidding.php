<?php

namespace App\Livewire;

use App\Models\Bid;
use App\Models\Load;
use App\Models\AdApplication;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class LoadBidding extends Component
{
    public $type = 'FTL';
    public $max_price;
    
    // Box 1: Info Barang
    public $title;
    public $item_name;
    public $weight_kg;
    public $koli;
    public $vehicle_type_needed;
    
    // Box 2: Info Pengirim
    public $sender_name;
    public $sender_phone;
    public $sender_address;
    
    // Box 3: Info Penerima
    public $receiver_name;
    public $receiver_phone;
    public $receiver_address;
    public $distance;
    
    public $bid_deadline;
    public $is_paylater = false;

    // For Driver
    public $bid_amounts = [];
    public $suggested_prices = [];

    // For Merchant Repost
    public $reposting_load_id = null;
    public $repost_suggested_price = null;

    public function mount()
    {
        // Auto-fill sender info with current user's profile if merchant
        if (Auth::user()->hasRole('merchant')) {
            $this->sender_name = Auth::user()->name;
        }
    }

    public function createLoad()
    {
        $this->validate([
            'type' => 'required|in:LTL,FTL',
            'max_price' => 'required|numeric|min:1',
            'title' => 'required|string|max:255',
            'item_name' => 'required|string|max:255',
            'weight_kg' => 'required|numeric|min:1',
            'koli' => 'nullable|numeric|min:1',
            'vehicle_type_needed' => 'required|string',
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:255',
            'sender_address' => 'required|string',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:255',
            'receiver_address' => 'required|string',
            'distance' => 'nullable|numeric',
            'bid_deadline' => 'nullable|date',
            'is_paylater' => 'boolean',
        ]);

        Load::create([
            'merchant_id' => Auth::id(),
            'type' => $this->type,
            'max_price' => $this->max_price,
            'title' => $this->title,
            'item_name' => $this->item_name,
            'weight_kg' => $this->weight_kg,
            'koli' => $this->koli,
            'vehicle_type_needed' => $this->vehicle_type_needed,
            'sender_name' => $this->sender_name,
            'sender_phone' => $this->sender_phone,
            'sender_address' => $this->sender_address,
            'receiver_name' => $this->receiver_name,
            'receiver_phone' => $this->receiver_phone,
            'receiver_address' => $this->receiver_address,
            'distance' => $this->distance,
            'bid_deadline' => $this->bid_deadline,
            'is_paylater' => $this->is_paylater,
            'status' => 'open',
            'escrow_status' => 'pending'
        ]);

        $this->reset([
            'title', 'item_name', 'weight_kg', 'koli', 'vehicle_type_needed',
            'sender_name', 'sender_phone', 'sender_address',
            'receiver_name', 'receiver_phone', 'receiver_address',
            'distance', 'bid_deadline', 'max_price', 'is_paylater'
        ]);
        
        session()->flash('message', 'Order muatan berhasil dibuat dan masuk ke bursa Bidding!');
    }

    public function submitBid($loadId)
    {
        if (!Auth::user()->hasRole('driver')) {
            session()->flash('error', 'Silakan pilih role sebagai Driver terlebih dahulu untuk bisa melakukan bid.');
            return;
        }

        $load = Load::findOrFail($loadId);

        $this->validate([
            'bid_amounts.'.$loadId => 'required|numeric|lt:'.$load->max_price,
        ], [
            'bid_amounts.'.$loadId.'.lt' => 'Harga bid harus lebih rendah dari harga maksimum Merchant.',
        ]);

        Bid::updateOrCreate(
            ['load_id' => $loadId, 'driver_id' => Auth::id()],
            ['amount' => $this->bid_amounts[$loadId], 'status' => 'pending']
        );

        session()->flash('message', 'Bid berhasil diajukan.');
    }

    public function rejectBid($loadId)
    {
        if (!Auth::user()->hasRole('driver')) {
            session()->flash('error', 'Silakan pilih role sebagai Driver terlebih dahulu untuk bisa memberikan saran harga.');
            return;
        }

        $load = Load::findOrFail($loadId);

        $this->validate([
            'suggested_prices.'.$loadId => 'required|numeric|min:1',
        ], [
            'suggested_prices.'.$loadId.'.required' => 'Mohon masukkan saran harga sebelum menolak.',
            'suggested_prices.'.$loadId.'.numeric' => 'Saran harga harus berupa angka.',
        ]);

        Bid::updateOrCreate(
            ['load_id' => $loadId, 'driver_id' => Auth::id()],
            ['amount' => 0, 'suggested_price' => $this->suggested_prices[$loadId], 'status' => 'rejected']
        );

        session()->flash('message', 'Bid ditolak. Terima kasih atas masukan harga Anda.');
    }

    public function repostLoad($loadId)
    {
        $oldLoad = Load::findOrFail($loadId);
        
        if ($oldLoad->merchant_id !== Auth::id()) {
            abort(403);
        }

        $this->type = $oldLoad->type;
        $this->max_price = $oldLoad->max_price;
        $this->title = $oldLoad->title;
        $this->item_name = $oldLoad->item_name;
        $this->weight_kg = $oldLoad->weight_kg;
        $this->koli = $oldLoad->koli;
        $this->vehicle_type_needed = $oldLoad->vehicle_type_needed;
        $this->sender_name = $oldLoad->sender_name;
        $this->sender_phone = $oldLoad->sender_phone;
        $this->sender_address = $oldLoad->sender_address;
        $this->receiver_name = $oldLoad->receiver_name;
        $this->receiver_phone = $oldLoad->receiver_phone;
        $this->receiver_address = $oldLoad->receiver_address;
        $this->distance = $oldLoad->distance;
        $this->is_paylater = $oldLoad->is_paylater;
        
        $this->reposting_load_id = $oldLoad->id;
        
        // Check if there was an average suggested price from rejected bids
        $rejectedBids = Bid::where('load_id', $loadId)->where('status', 'rejected')->get();
        if ($rejectedBids->count() > 0) {
            $this->repost_suggested_price = $rejectedBids->avg('suggested_price');
        } else {
            $this->repost_suggested_price = null;
        }

        session()->flash('message', 'Data muatan lama berhasil dimuat. Silakan sesuaikan harga atau batas waktu sebelum membuat ulang.');
    }
    
    public function applySuggestedPrice()
    {
        if ($this->repost_suggested_price) {
            $this->max_price = $this->repost_suggested_price;
        }
    }


    public function approveBid($bidId)
    {
        $bid = Bid::findOrFail($bidId);
        $load = $bid->cargo;

        // Ensure user is the merchant who created the load
        if ($load->merchant_id !== Auth::id()) {
            abort(403);
        }

        // Reject all other pending bids for this load
        Bid::where('load_id', $load->id)->where('id', '!=', $bid->id)->update(['status' => 'rejected']);

        $bid->update(['status' => 'accepted']);
        $load->update(['status' => 'in_transit']);
        
        // Let DriverCockpit create the trip when driver visits, or create trip here.
        // Usually creating trip here is better.
        // We'll let DriverCockpit handle it (as it's currently doing) or we can create it here.

        session()->flash('message', 'Bid berhasil disetujui! Supir segera menuju lokasi.');
    }

    public $search = '';

    public function render()
    {
        $user = Auth::user();

        $query = Load::query();

        if ($user->hasRole('merchant')) {
            $query->where('merchant_id', $user->id);
            // Show both open and closed for merchant so they can repost
        } else {
            $query->where('status', 'open');
            
            // Restrict LTL and Lion Parcel (Admin) loads to VIP Subscribers
            if (!$user->is_subscribed) {
                $query->where('type', '!=', 'LTL');
                
                $query->whereHas('merchant', function ($q) {
                    $q->whereDoesntHave('roles', function ($r) {
                        $r->where('name', 'admin');
                    });
                });
            }
        }

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->whereHas('merchant', function($q2) {
                    $q2->where('name', 'like', '%' . $this->search . '%');
                })->orWhere('type', 'like', '%' . $this->search . '%');
            });
        }

        $loads = $query->latest()->get();

        $topBidMerchants = [];
        $activeAd = null;

        if (!$user->hasRole('merchant')) {
            $modeSetting = Setting::where('key', 'top_bid_mode')->first();
            $mode = $modeSetting ? $modeSetting->value : 'auto';

            if ($mode === 'manual') {
                $merchantsSetting = Setting::where('key', 'top_bid_merchants')->first();
                $merchantIds = $merchantsSetting && $merchantsSetting->value ? json_decode($merchantsSetting->value, true) : [];
                $topBidMerchants = User::whereIn('id', $merchantIds)->get();
            } else {
                // Dummy Auto: just grab 3 merchants
                $topBidMerchants = User::role('merchant')->take(3)->get();
            }

            $activeAdApp = AdApplication::where('status', 'approved')->latest()->first();
            if ($activeAdApp) {
                $activeAd = $activeAdApp->merchant;
            }
        }

        $recommendedDrivers = collect();
        if ($user->hasRole('merchant') && $user->is_subscribed) {
            $recommendedDrivers = User::role('driver')
                ->where('is_subscribed', true)
                ->withAvg('ratingsAsRatee', 'score')
                ->orderByDesc('ratings_as_ratee_avg_score')
                ->take(3)
                ->get();
        }

        return view('livewire.load-bidding', [
            'loads' => $loads,
            'isMerchant' => $user->hasRole('merchant'),
            'topBidMerchants' => $topBidMerchants,
            'activeAd' => $activeAd,
            'recommendedDrivers' => $recommendedDrivers,
        ]);
    }
}
