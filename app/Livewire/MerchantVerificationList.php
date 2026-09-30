<?php

namespace App\Livewire;

use App\Enums\AssessmentStatus;
use App\Models\Assessment;
use Livewire\Component;

class MerchantVerificationList extends Component
{
    public function render()
    {
        $pendingAssessments = Assessment::with('driver')
            ->whereHas('items.criterion', function ($query) {
                $query->where('target_role', 'merchant');
            })
            ->where('status', AssessmentStatus::SUBMITTED)
            ->latest()
            ->get();

        return view('livewire.merchant-verification-list', ['pendingAssessments' => $pendingAssessments])->layout('layouts.app');
    }
}
