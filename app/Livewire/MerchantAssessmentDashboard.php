<?php

namespace App\Livewire;

use App\Enums\AssessmentLevel;
use App\Enums\AssessmentStatus;
use App\Enums\CaStatus;
use App\Models\Assessment;
use App\Models\CorrectiveAction;
use Livewire\Component;

class MerchantAssessmentDashboard extends Component
{
    public function render()
    {
        $assessments = Assessment::whereHas('items.criterion', function ($query) {
            $query->where('target_role', 'merchant');
        })->get();

        $totalAssessments = $assessments->count();
        $avgScore = $totalAssessments > 0 ? $assessments->avg('total_score') : 0;

        $trustedCount = $assessments->where('level', AssessmentLevel::GOLD)->count();
        $verifiedCount = $assessments->where('level', AssessmentLevel::SILVER)->count();
        $basicCount = $assessments->where('level', AssessmentLevel::BRONZE)->count();
        $restrictedCount = $assessments->where('level', AssessmentLevel::NOT_ELIGIBLE)->count();

        $caBaseQuery = CorrectiveAction::whereHas('assessmentItem.criterion', function ($query) {
            $query->where('target_role', 'merchant');
        });

        $caOpen = (clone $caBaseQuery)->where('status', CaStatus::OPEN)->count();
        $caInProgress = (clone $caBaseQuery)->where('status', CaStatus::IN_PROGRESS)->count();
        $caClosed = (clone $caBaseQuery)->where('status', CaStatus::CLOSED)->count();

        $pendingAssessments = Assessment::with('driver')
            ->whereHas('items.criterion', function ($query) {
                $query->where('target_role', 'merchant');
            })
            ->where('status', AssessmentStatus::SUBMITTED)
            ->latest()
            ->get();

        return view('livewire.merchant-assessment-dashboard', [
            'totalAssessments' => $totalAssessments,
            'avgScore' => number_format($avgScore, 1),
            'trustedCount' => $trustedCount,
            'verifiedCount' => $verifiedCount,
            'basicCount' => $basicCount,
            'restrictedCount' => $restrictedCount,
            'caOpen' => $caOpen,
            'caInProgress' => $caInProgress,
            'caClosed' => $caClosed,
            'pendingAssessments' => $pendingAssessments,
        ])->layout('layouts.app');
    }
}
