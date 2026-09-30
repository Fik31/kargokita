<?php

namespace App\Livewire;

use App\Models\VerificationRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class VerificationForm extends Component
{
    public $type;

    public function mount()
    {
        $user = Auth::user();
        if ($user->hasRole('merchant')) {
            $this->type = 'merchant';
        } elseif ($user->hasRole('driver')) {
            $this->type = 'driver';
        } else {
            // Fallback for edge cases (e.g., admin)
            $this->type = 'merchant';
        }
    }

    // Basic (Bronze)
    public $phone = '';

    public $address = '';

    // Advanced (Silver)
    public $ktp_number = '';

    public $npwp_number = '';

    // Merchant (Gold)
    public $nib = '';

    public $company_name = '';

    // Driver (Gold)
    public $sim_number = '';

    public $stnk_number = '';

    public $vehicle_plate = '';

    public $vehicle_type = '';

    public $vehicle_capacity = '';

    public function submit()
    {
        $this->validate([
            'type' => 'required|in:merchant,driver',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'ktp_number' => 'nullable|string|max:50',
            'npwp_number' => 'nullable|string|max:50',
            'nib' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'sim_number' => 'nullable|string|max:255',
            'stnk_number' => 'nullable|string|max:255',
            'vehicle_plate' => 'nullable|string|max:255',
            'vehicle_type' => 'nullable|string|max:255',
            'vehicle_capacity' => 'nullable|numeric',
        ]);

        $data = [
            'phone' => $this->phone,
            'address' => $this->address,
            'ktp_number' => $this->ktp_number,
            'npwp_number' => $this->npwp_number,
        ];

        if ($this->type === 'merchant') {
            $data['nib'] = $this->nib;
            $data['company_name'] = $this->company_name;
        } else {
            $data['sim_number'] = $this->sim_number;
            $data['stnk_number'] = $this->stnk_number;
            $data['vehicle_plate'] = $this->vehicle_plate;
            $data['vehicle_type'] = $this->vehicle_type;
            $data['vehicle_capacity'] = $this->vehicle_capacity;
        }

        // Determine Tier
        $tier = 'bronze';

        $hasBasic = ! empty($this->phone) && ! empty($this->address);
        $hasSilver = $hasBasic && ! empty($this->ktp_number) && ! empty($this->npwp_number);

        if ($this->type === 'merchant') {
            $hasGold = $hasSilver && ! empty($this->nib) && ! empty($this->company_name);

            if ($hasGold) {
                $tier = 'gold';
            } elseif ($hasSilver) {
                $tier = 'silver';
            }
        }

        $user = Auth::user();

        // Update tier HANYA untuk merchant. Untuk driver, tier ditentukan dari penilaian HSE (Pembobotan).
        if ($this->type === 'merchant') {
            $user->update(['tier' => $tier]);
        }

        // Ensure user has correct role based on type selected
        if (! $user->hasRole($this->type)) {
            $user->syncRoles([$this->type]);
        }

        VerificationRequest::create([
            'user_id' => $user->id,
            'type' => $this->type,
            'data' => $data,
            'status' => 'pending', // Ubah ke pending agar admin/HSE bisa melihat data yang butuh diverifikasi
        ]);

        if ($this->type === 'merchant') {
            session()->flash('message', 'Profil berhasil disimpan. Anda mendapatkan tier: '.strtoupper($tier));
        } else {
            session()->flash('message', 'Profil berhasil disimpan. Silakan lanjut ke menu Penilaian HSE (Driver Cockpit) untuk menentukan Tier Anda.');
        }
    }

    public function render()
    {
        $existingRequest = VerificationRequest::where('user_id', Auth::id())
            ->latest()
            ->first();

        // Optionally pre-fill if request exists
        if ($existingRequest && empty($this->phone)) {
            $this->type = $existingRequest->type;
            $data = $existingRequest->data ?? [];
            $this->phone = $data['phone'] ?? '';
            $this->address = $data['address'] ?? '';
            $this->ktp_number = $data['ktp_number'] ?? '';
            $this->npwp_number = $data['npwp_number'] ?? '';
            $this->nib = $data['nib'] ?? '';
            $this->company_name = $data['company_name'] ?? '';
            $this->sim_number = $data['sim_number'] ?? '';
            $this->stnk_number = $data['stnk_number'] ?? '';
            $this->vehicle_plate = $data['vehicle_plate'] ?? '';
            $this->vehicle_type = $data['vehicle_type'] ?? '';
            $this->vehicle_capacity = $data['vehicle_capacity'] ?? '';
        }

        return view('livewire.verification-form', [
            'existingRequest' => $existingRequest,
        ]);
    }
}
