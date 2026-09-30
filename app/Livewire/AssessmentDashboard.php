<?php

namespace App\Livewire;

use App\Enums\AssessmentLevel;
use App\Enums\AssessmentStatus;
use App\Enums\CaStatus;
use App\Models\Assessment;
use App\Models\CorrectiveAction;
use Livewire\Component;

class AssessmentDashboard extends Component
{
    public function render()
    {
        $assessments = Assessment::whereHas('items.criterion', function ($query) {
            $query->where('target_role', 'driver');
        })->get();

        $totalAssessments = $assessments->count();
        $avgScore = $totalAssessments > 0 ? $assessments->avg('total_score') : 0;

        $goldCount = $assessments->where('level', AssessmentLevel::GOLD)->count();
        $silverCount = $assessments->where('level', AssessmentLevel::SILVER)->count();
        $bronzeCount = $assessments->where('level', AssessmentLevel::BRONZE)->count();
        $notEligibleCount = $assessments->where('level', AssessmentLevel::NOT_ELIGIBLE)->count();

        // CA open across all might be okay, or filter CA by assessment's target_role. Let's just filter CA by driver assessments.
        $caBaseQuery = CorrectiveAction::whereHas('assessmentItem.criterion', function ($query) {
            $query->where('target_role', 'driver');
        });

        $caOpen = (clone $caBaseQuery)->where('status', CaStatus::OPEN)->count();
        $caInProgress = (clone $caBaseQuery)->where('status', CaStatus::IN_PROGRESS)->count();
        $caClosed = (clone $caBaseQuery)->where('status', CaStatus::CLOSED)->count();

        $pendingAssessments = Assessment::with('driver')
            ->whereHas('items.criterion', function ($query) {
                $query->where('target_role', 'driver');
            })
            ->where('status', AssessmentStatus::SUBMITTED)
            ->latest()
            ->get();

        return view('livewire.assessment-dashboard', [
            'totalAssessments' => $totalAssessments,
            'avgScore' => number_format($avgScore, 1),
            'goldCount' => $goldCount,
            'silverCount' => $silverCount,
            'bronzeCount' => $bronzeCount,
            'notEligibleCount' => $notEligibleCount,
            'caOpen' => $caOpen,
            'caInProgress' => $caInProgress,
            'caClosed' => $caClosed,
            'pendingAssessments' => $pendingAssessments,
        ]);
    }
}
