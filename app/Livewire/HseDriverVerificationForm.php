<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\VerificationRequest;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class HseDriverVerificationForm extends Component
{
    public $request_id;
    public $verificationRequest;

    // We will let HSE check each bundle
    public $verify_identity = false;
    public $verify_sim = false;
    public $verify_vehicle = false;
    public $verify_compliance = false;
    public $verify_risk = false;
    public $verify_area = false;
    public $verify_bank = false;

    public function mount($request_id)
    {
        $this->request_id = $request_id;
        $this->verificationRequest = VerificationRequest::with('user')->findOrFail($request_id);
    }

    public function submit()
    {
        // Calculate score
        $totalScore = 0;
        if ($this->verify_identity) $totalScore += 20;
        if ($this->verify_sim) $totalScore += 20;
        if ($this->verify_vehicle) $totalScore += 20;
        if ($this->verify_compliance) $totalScore += 20;
        if ($this->verify_risk) $totalScore += 10;
        if ($this->verify_area) $totalScore += 5;
        if ($this->verify_bank) $totalScore += 5;

        // Determine tier
        $tier = 'not_eligible';
        if ($totalScore >= 90) {
            $tier = 'gold';
        } elseif ($totalScore >= 80) {
            $tier = 'silver';
        } elseif ($totalScore >= 70) {
            $tier = 'bronze';
        }

        // Update user tier
        $user = $this->verificationRequest->user;
        $user->update(['tier' => $tier]);
        
        // Update request status
        $this->verificationRequest->update([
            'status' => 'approved',
            // Save the HSE checks back if needed, but for now we just approve.
        ]);

        session()->flash('message', "Data Diri Driver Berhasil Diverifikasi. Total Skor: {$totalScore}%. Tier yang diberikan: " . strtoupper($tier));
        return redirect()->route('admin.assessment.verification-list');
    }

    public function reject()
    {
        $this->verificationRequest->update([
            'status' => 'rejected'
        ]);

        session()->flash('message', "Pendaftaran ditolak karena data tidak valid.");
        return redirect()->route('admin.assessment.verification-list');
    }

    public function render()
    {
        $data = $this->verificationRequest->data;

        return view('livewire.hse-driver-verification-form', [
            'data' => $data,
        ]);
    }
}
