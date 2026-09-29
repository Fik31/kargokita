<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Assessment;
use App\Models\CorrectiveAction;
use App\Enums\AssessmentLevel;
use App\Enums\CaStatus;

class AssessmentDashboard extends Component
{
    public function render()
    {
        $assessments = Assessment::all();
        
        $totalAssessments = $assessments->count();
        $avgScore = $totalAssessments > 0 ? $assessments->avg('total_score') : 0;
        
        $goldCount = $assessments->where('level', AssessmentLevel::GOLD)->count();
        $silverCount = $assessments->where('level', AssessmentLevel::SILVER)->count();
        $bronzeCount = $assessments->where('level', AssessmentLevel::BRONZE)->count();
        $notEligibleCount = $assessments->where('level', AssessmentLevel::NOT_ELIGIBLE)->count();

        $caOpen = CorrectiveAction::where('status', CaStatus::OPEN)->count();
        $caInProgress = CorrectiveAction::where('status', CaStatus::IN_PROGRESS)->count();
        $caClosed = CorrectiveAction::where('status', CaStatus::CLOSED)->count();

        $pendingAssessments = Assessment::with('driver')->where('status', \App\Enums\AssessmentStatus::SUBMITTED)->latest()->get();

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
