<?php

namespace App\Livewire;

use App\Enums\AssessmentLevel;
use App\Enums\AssessmentStatus;
use App\Enums\ComplianceStatus;
use App\Models\Assessment;
use App\Models\AssessmentCriterion;
use App\Models\AssessmentItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class MerchantSelfAssessmentForm extends Component
{
    use WithFileUploads;

    public $criteriaGrouped = [];

    public $answers = [];

    public $activeTab = 'LEGAL IDENTITY';

    public $company_name = '';

    public $nib_number = '';

    public function mount()
    {
        $criteria = AssessmentCriterion::where('target_role', 'merchant')->get();
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
            'company_name' => 'required|string',
            'nib_number' => 'required|string',
            'answers.*.evidence' => 'nullable|image|max:5120', // Max 5MB
        ]);

        $user = Auth::user();

        // Check if there is an existing draft/pending assessment, or just create new
        $assessment = Assessment::create([
            'driver_id' => $user->id, // Reusing driver_id as the user_id for assessment
            'vehicle_id' => null,
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
                'status' => ComplianceStatus::NA,
                'notes' => $answer['notes'] ?? null,
                'evidence_path' => $evidencePath,
            ]);
        }

        session()->flash('message', 'Data Registrasi Merchant berhasil dikirim! Data Anda sedang diverifikasi oleh admin, silakan tunggu maksimal 1x24 jam.');

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.merchant-self-assessment-form')->layout('layouts.app');
    }
}
