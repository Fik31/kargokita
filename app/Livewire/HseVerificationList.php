<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Assessment;

class HseVerificationList extends Component
{
    public function render()
    {
        $pendingDataVerifications = \App\Models\VerificationRequest::with('user')
            ->where('type', 'driver')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $pendingAssessments = Assessment::with('driver')
            ->whereHas('items.criterion', function ($query) {
                $query->where('target_role', 'driver');
            })
            ->where('status', \App\Enums\AssessmentStatus::SUBMITTED)
            ->latest()
            ->get();

        return view('livewire.hse-verification-list', [
            'pendingAssessments' => $pendingAssessments,
            'pendingDataVerifications' => $pendingDataVerifications,
        ]);
    }
}
