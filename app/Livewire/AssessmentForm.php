<?php

namespace App\Livewire;

use App\Enums\AssessmentStatus;
use App\Enums\ComplianceStatus;
use App\Models\Assessment;
use App\Models\AssessmentCriterion;
use App\Models\AssessmentItem;
use App\Models\CorrectiveAction;
use App\Services\AssessmentScoringService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AssessmentForm extends Component
{
    public $assessment_id;

    public $assessment;

    public $criteriaGrouped = [];

    public $answers = [];

    public $activeTab = 'DRIVER';

    public function mount($assessment_id = null)
    {
        $this->assessment_id = $assessment_id;
        $targetRole = 'driver';

        if ($this->assessment_id) {
            $this->assessment = Assessment::with(['items.criterion'])->findOrFail($this->assessment_id);

            if ($this->assessment->items->count() > 0) {
                $targetRole = $this->assessment->items->first()->criterion->target_role;
            }

            // Map existing items
            $existingItems = $this->assessment->items->keyBy('criterion_id');

            $criteria = AssessmentCriterion::where('target_role', $targetRole)->get();
            foreach ($criteria as $c) {
                $this->criteriaGrouped[$c->category][] = $c;

                $item = $existingItems->get($c->id);
                $this->answers[$c->id] = [
                    'item_id' => $item ? $item->id : null,
                    'status' => $item && $item->status != ComplianceStatus::NA ? $item->status->value : ComplianceStatus::COMPLY->value,
                    'notes' => $item ? $item->notes : '',
                    'evidence_path' => $item ? $item->evidence_path : null, // Display only
                    'pic_ca' => null,
                    'due_date' => null,
                ];
            }
        } else {
            // For manual creation if needed
            $criteria = AssessmentCriterion::where('target_role', $targetRole)->get();
            foreach ($criteria as $c) {
                $this->criteriaGrouped[$c->category][] = $c;
                $this->answers[$c->id] = [
                    'item_id' => null,
                    'status' => ComplianceStatus::COMPLY->value,
                    'notes' => '',
                    'evidence_path' => null,
                    'pic_ca' => null,
                    'due_date' => null,
                ];
            }
        }

        if (count($this->criteriaGrouped) > 0) {
            $this->activeTab = array_keys($this->criteriaGrouped)[0];
        }
    }

    public function submit(AssessmentScoringService $scoringService)
    {
        if (! $this->assessment) {
            $this->assessment = Assessment::create([
                'driver_id' => null,
                'vehicle_id' => null,
                'assessor_id' => Auth::id(),
                'date' => date('Y-m-d'),
                'status' => AssessmentStatus::VERIFIED,
            ]);
        } else {
            $this->assessment->update([
                'assessor_id' => Auth::id(),
                'status' => AssessmentStatus::VERIFIED,
            ]);
        }

        foreach ($this->answers as $criterionId => $answer) {
            if ($answer['item_id']) {
                $item = AssessmentItem::find($answer['item_id']);
                $item->update([
                    'status' => $answer['status'],
                    'notes' => $answer['notes'] ?? null,
                ]);
            } else {
                $item = AssessmentItem::create([
                    'assessment_id' => $this->assessment->id,
                    'criterion_id' => $criterionId,
                    'status' => $answer['status'],
                    'notes' => $answer['notes'] ?? null,
                ]);
            }

            if ($answer['status'] === ComplianceStatus::NON_COMPLY->value) {
                CorrectiveAction::updateOrCreate(
                    ['assessment_item_id' => $item->id],
                    [
                        'pic_id' => $answer['pic_ca'] ?? null,
                        'due_date' => $answer['due_date'] ?? null,
                        'notes' => $answer['notes'] ?? 'Tindak lanjut dari verifikasi.',
                        'status' => 'OPEN',
                    ]
                );
            }
        }

        $scoringService->calculateScore($this->assessment);

        session()->flash('message', 'Assessment berhasil diverifikasi. Tier disinkronisasi. Hasil: '.$this->assessment->level->value);

        $targetRole = 'driver';
        if ($this->assessment->items->count() > 0) {
            $targetRole = $this->assessment->items->first()->criterion->target_role;
        }

        if ($targetRole === 'merchant') {
            return redirect()->route('admin.merchant-assessment.verification-list');
        }

        return redirect()->route('admin.assessment.dashboard');
    }

    public function render()
    {
        return view('livewire.assessment-form');
    }
}
