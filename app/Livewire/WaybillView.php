<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Trip;
use App\Models\Waybill;

class WaybillView extends Component
{
    public $trip_id;
    public $trip;
    public $waybill;
    
    public $driver_signature_data;
    public $merchant_signature_data;

    public function mount($trip_id)
    {
        $this->trip_id = $trip_id;
        $this->trip = Trip::with(['cargo.merchant', 'driver'])->findOrFail($trip_id);
        $this->waybill = Waybill::firstOrCreate(['trip_id' => $trip_id]);
    }
    
    public function saveDriverSignature()
    {
        if ($this->driver_signature_data) {
            $this->waybill->update([
                'driver_signature' => $this->driver_signature_data,
                'status' => $this->waybill->merchant_signature ? 'completed' : 'driver_signed'
            ]);
            session()->flash('message', 'Tanda tangan Driver berhasil disimpan.');
        }
    }
    
    public function saveMerchantSignature()
    {
        if ($this->merchant_signature_data) {
            $this->waybill->update([
                'merchant_signature' => $this->merchant_signature_data,
                'status' => $this->waybill->driver_signature ? 'completed' : 'merchant_signed'
            ]);
            session()->flash('message', 'Tanda tangan Merchant berhasil disimpan.');
        }
    }

    public function render()
    {
        return view('livewire.waybill-view')->layout('layouts.app');
    }
}
