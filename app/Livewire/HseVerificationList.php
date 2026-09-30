<?php

namespace App\Livewire;

use App\Enums\AssessmentStatus;
use App\Models\Assessment;
use App\Models\VerificationRequest;
use Livewire\Component;

class HseVerificationList extends Component
{
    public function render()
    {
        $pendingDataVerifications = VerificationRequest::with('user')
            ->where('type', 'driver')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $pendingAssessments = Assessment::with('driver')
            ->whereHas('items.criterion', function ($query) {
                $query->where('target_role', 'driver');
            })
            ->where('status', AssessmentStatus::SUBMITTED)
            ->latest()
            ->get();

        return view('livewire.hse-verification-list', [
            'pendingAssessments' => $pendingAssessments,
            'pendingDataVerifications' => $pendingDataVerifications,
        ]);
    }
}
