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

    public function submit()
    {
        $this->validate([
            'answers.*.evidence' => 'nullable|image|max:5120', // Max 5MB
        ]);

        $user = Auth::user();

        $assessment = Assessment::create([
            'driver_id' => $user->id,
            'vehicle_id' => null, // Placeholder, can be linked to a vehicle model later
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
        return redirect()->route('dashboard'); 
    }

    public function render()
    {
        return view('livewire.driver-self-assessment-form')->layout('layouts.app');
    }
}
