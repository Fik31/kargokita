<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\AssessmentCriterion;
use App\Models\Assessment;
use App\Models\AssessmentItem;
use App\Enums\AssessmentStatus;
use App\Enums\AssessmentLevel;
use App\Enums\ComplianceStatus;
use Illuminate\Support\Facades\Auth;

class DriverSelfAssessmentForm extends Component
{
    use WithFileUploads;

    public $criteriaGrouped = [];
    public $answers = []; 
    public $activeTab = 'DRIVER';

    public function mount()
    {
        $criteria = AssessmentCriterion::all();
        foreach ($criteria as $c) {
            $this->criteriaGrouped[$c->category][] = $c;
            $this->answers[$c->id] = [
                'evidence' => null,
                'notes' => '',
            ];
        }
        if (count($this->criteriaGrouped) > 0) {
            $this->activeTab = array_keys($this->criteriaGrouped)[0];
        }
    }

    public $vehicle_type = '';
    public $license_plate = '';

    public function submit()
    {
        $this->validate([
            'vehicle_type' => 'required|string',
            'license_plate' => 'required|string',
            'answers.*.evidence' => 'nullable|image|max:5120', // Max 5MB
        ]);

        $user = Auth::user();

        // Check if vehicle exists or create new
        $vehicle = \App\Models\Vehicle::updateOrCreate(
            ['license_plate' => $this->license_plate],
            [
                'owner_id' => $user->id,
                'driver_id' => $user->id,
                'type' => $this->vehicle_type,
                'capacity_kg' => 0, // Default capacity, could be parsed from type later
            ]
        );

        // Or we could just use the latest existing assessment if it's draft, but for now we create new
        $assessment = Assessment::create([
            'driver_id' => $user->id,
            'vehicle_id' => $vehicle->id,
            'date' => date('Y-m-d'),
            'status' => AssessmentStatus::SUBMITTED,
            'level' => AssessmentLevel::NOT_ELIGIBLE,
            'total_score' => 0,
            'has_mandatory_failure' => false,
        ]);

        foreach ($this->answers as $criterionId => $answer) {
            $evidencePath = null;
            if (isset($answer['evidence']) && $answer['evidence']) {
                $evidencePath = $answer['evidence']->store('assessment_evidences', 'public');
            }

            AssessmentItem::create([
                'assessment_id' => $assessment->id,
                'criterion_id' => $criterionId,
                'status' => ComplianceStatus::NA, // Default status before HSE checks
                'notes' => $answer['notes'] ?? null,
                'evidence_path' => $evidencePath,
            ]);
        }

        // Assign the driver role tentatively (if they don't have it)
        if (!$user->hasRole('driver')) {
            $user->assignRole('driver');
        }

        session()->flash('message', 'Data Assessment berhasil dikirim! Tim HSE kami akan segera memverifikasi data Anda untuk penentuan Tier.');
        return redirect()->route('feed'); 
    }

    public function render()
    {
        return view('livewire.driver-self-assessment-form')->layout('layouts.app');
    }
}
