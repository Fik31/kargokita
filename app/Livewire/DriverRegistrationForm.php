<?php

namespace App\Livewire;

use App\Models\VerificationRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class DriverRegistrationForm extends Component
{
    use WithFileUploads;

    // Identity Verification (20%)
    public $ktp_photo;

    public $full_name = '';

    public $nik = '';

    public $dob = '';

    public $address = '';

    public $phone = '';

    public $emergency_contact = '';

    public $profile_photo;

    // SIM Verification (20%)
    public $sim_photo;

    public $sim_type = '';

    public $sim_number = '';

    public $sim_validity = '';

    public $driving_experience = '';

    public $operational_area = '';

    public $cargo_type = '';

    // Vehicle Verification (20%)
    public $license_plate = '';

    public $vehicle_type = '';

    public $vehicle_brand = '';

    public $vehicle_year = '';

    public $stnk_photo;

    public $kir_photo;

    public $capacity_kg = '';

    public $capacity_cbm = '';

    public $box_dimension = '';

    public $vehicle_photo;

    public $gps_device = '';

    // Vehicle Compliance (20%)
    public $apar = false;

    public $stopper = false;

    public $kanebo = false;

    public $safety_tools = false; // Dongkrak, P3K, Segitiga

    // Risk Screening (10%)
    public $skck_photo;

    // Area Coverage (5%)
    public $domicile = '';

    // Bank/Payment Verification (5%)
    public $bank_account_photo;

    public function submit()
    {
        $this->validate([
            // Identity
            'ktp_photo' => 'required|image|max:5120',
            'full_name' => 'required|string',
            'nik' => 'required|string',
            'dob' => 'required|date',
            'address' => 'required|string',
            'phone' => 'required|string',
            'emergency_contact' => 'required|string',
            'profile_photo' => 'required|image|max:5120',

            // SIM
            'sim_photo' => 'required|image|max:5120',
            'sim_type' => 'required|string',
            'sim_number' => 'required|string',
            'sim_validity' => 'required|date',
            'driving_experience' => 'required|string',
            'operational_area' => 'required|string',
            'cargo_type' => 'required|string',

            // Vehicle
            'license_plate' => 'required|string',
            'vehicle_type' => 'required|string',
            'vehicle_brand' => 'required|string',
            'vehicle_year' => 'required|numeric',
            'stnk_photo' => 'required|image|max:5120',
            'kir_photo' => 'required|image|max:5120',
            'capacity_kg' => 'required|numeric',
            'capacity_cbm' => 'required|numeric',
            'box_dimension' => 'required|string',
            'vehicle_photo' => 'required|image|max:5120',
            'gps_device' => 'nullable|string',

            // Compliance
            'apar' => 'accepted',
            'stopper' => 'accepted',
            'kanebo' => 'accepted',
            'safety_tools' => 'accepted',

            // Risk
            'skck_photo' => 'required|image|max:5120',

            // Area
            'domicile' => 'required|string',

            // Bank
            'bank_account_photo' => 'required|image|max:5120',
        ], [
            'required' => 'Wajib diisi/diupload untuk mendapatkan bobot penuh.',
            'accepted' => 'Wajib dicentang (memiliki) untuk memenuhi Vehicle Compliance.',
        ]);

        $user = Auth::user();

        // Save files
        $ktpPath = $this->ktp_photo->store('driver_documents', 'public');
        $profilePath = $this->profile_photo->store('driver_documents', 'public');
        $simPath = $this->sim_photo->store('driver_documents', 'public');
        $stnkPath = $this->stnk_photo->store('driver_documents', 'public');
        $kirPath = $this->kir_photo->store('driver_documents', 'public');
        $vehiclePath = $this->vehicle_photo->store('driver_documents', 'public');
        $skckPath = $this->skck_photo->store('driver_documents', 'public');
        $bankPath = $this->bank_account_photo->store('driver_documents', 'public');

        $data = [
            'identity' => [
                'full_name' => $this->full_name,
                'nik' => $this->nik,
                'dob' => $this->dob,
                'address' => $this->address,
                'phone' => $this->phone,
                'emergency_contact' => $this->emergency_contact,
                'ktp_photo' => $ktpPath,
                'profile_photo' => $profilePath,
            ],
            'sim' => [
                'sim_type' => $this->sim_type,
                'sim_number' => $this->sim_number,
                'sim_validity' => $this->sim_validity,
                'driving_experience' => $this->driving_experience,
                'operational_area' => $this->operational_area,
                'cargo_type' => $this->cargo_type,
                'sim_photo' => $simPath,
            ],
            'vehicle' => [
                'license_plate' => $this->license_plate,
                'vehicle_type' => $this->vehicle_type,
                'vehicle_brand' => $this->vehicle_brand,
                'vehicle_year' => $this->vehicle_year,
                'capacity_kg' => $this->capacity_kg,
                'capacity_cbm' => $this->capacity_cbm,
                'box_dimension' => $this->box_dimension,
                'gps_device' => $this->gps_device,
                'stnk_photo' => $stnkPath,
                'kir_photo' => $kirPath,
                'vehicle_photo' => $vehiclePath,
            ],
            'compliance' => [
                'apar' => $this->apar,
                'stopper' => $this->stopper,
                'kanebo' => $this->kanebo,
                'safety_tools' => $this->safety_tools,
            ],
            'risk' => [
                'skck_photo' => $skckPath,
            ],
            'area' => [
                'domicile' => $this->domicile,
            ],
            'bank' => [
                'bank_account_photo' => $bankPath,
            ],
        ];

        // Store to VerificationRequest
        VerificationRequest::create([
            'user_id' => $user->id,
            'type' => 'driver',
            'data' => $data,
            'status' => 'pending',
        ]);

        session()->flash('message', 'Pendaftaran Driver berhasil dikirim! Data Anda sedang diverifikasi oleh tim HSE, silakan tunggu maksimal 1x24 jam.');

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.driver-registration-form')->layout('layouts.app');
    }
}
