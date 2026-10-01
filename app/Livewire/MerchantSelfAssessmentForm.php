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

    public $company_name = '';

    public $nib_photo;

    public $npwp_photo;

    public $company_address = '';

    // PIC Verification (M02)
    public $pic_ktp_photo;

    public $pic_name = '';

    public $pic_phone = '';

    public $pic_email = '';

    public $pic_otp = '';

    // Operational Readiness (M07)
    public $op_pickup_location = '';

    public $op_operational_hours = '';

    public $op_loading_readiness = '';

    public $op_contact_person = '';

    public $op_location_photo;

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
    }

    public function submit()
    {
        $this->validate([
            'company_name' => 'required|string',
            'company_address' => 'required|string',
            'nib_photo' => 'required|image|max:5120',
            'npwp_photo' => 'required|image|max:5120',
            // M02
            'pic_ktp_photo' => 'required|image|max:5120',
            'pic_name' => 'required|string',
            'pic_phone' => 'required|string',
            'pic_email' => 'required|email',
            'pic_otp' => 'required|string',
            // M07
            'op_pickup_location' => 'required|string',
            'op_operational_hours' => 'required|string',
            'op_loading_readiness' => 'required|string',
            'op_contact_person' => 'required|string',
            'op_location_photo' => 'required|image|max:5120',

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

        $m01 = AssessmentCriterion::where('code', 'M01')->first();
        if ($m01) {
            $nibPath = $this->nib_photo ? $this->nib_photo->store('assessment_evidences', 'public') : null;
            $npwpPath = $this->npwp_photo ? $this->npwp_photo->store('assessment_evidences', 'public') : null;

            AssessmentItem::create([
                'assessment_id' => $assessment->id,
                'criterion_id' => $m01->id,
                'status' => ComplianceStatus::NA,
                'notes' => json_encode([
                    'company_name' => $this->company_name,
                    'company_address' => $this->company_address,
                    'npwp_path' => $npwpPath,
                ]),
                'evidence_path' => $nibPath,
            ]);
        }

        $m02 = AssessmentCriterion::where('code', 'M02')->first();
        if ($m02) {
            $ktpPath = $this->pic_ktp_photo ? $this->pic_ktp_photo->store('assessment_evidences', 'public') : null;
            AssessmentItem::create([
                'assessment_id' => $assessment->id,
                'criterion_id' => $m02->id,
                'status' => ComplianceStatus::NA,
                'notes' => json_encode([
                    'pic_name' => $this->pic_name,
                    'pic_phone' => $this->pic_phone,
                    'pic_email' => $this->pic_email,
                    'pic_otp' => $this->pic_otp,
                ]),
                'evidence_path' => $ktpPath,
            ]);
        }

        $m07 = AssessmentCriterion::where('code', 'M07')->first();
        if ($m07) {
            $locationPath = $this->op_location_photo ? $this->op_location_photo->store('assessment_evidences', 'public') : null;
            AssessmentItem::create([
                'assessment_id' => $assessment->id,
                'criterion_id' => $m07->id,
                'status' => ComplianceStatus::NA,
                'notes' => json_encode([
                    'pickup_location' => $this->op_pickup_location,
                    'operational_hours' => $this->op_operational_hours,
                    'loading_readiness' => $this->op_loading_readiness,
                    'contact_person' => $this->op_contact_person,
                ]),
                'evidence_path' => $locationPath,
            ]);
        }

        foreach ($this->answers as $criterionId => $answer) {
            $criterion = AssessmentCriterion::find($criterionId);
            if ($criterion && in_array($criterion->code, ['M01', 'M02', 'M07'])) {
                continue;
            }

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
