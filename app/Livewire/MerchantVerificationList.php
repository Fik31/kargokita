<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Assessment;

class MerchantVerificationList extends Component
{
    public function render()
    {
        $pendingAssessments = Assessment::with('driver')
            ->whereHas('items.criterion', function ($query) {
                $query->where('target_role', 'merchant');
            })
            ->where('status', \App\Enums\AssessmentStatus::SUBMITTED)
            ->latest()
            ->get();

        return view('livewire.merchant-verification-list', ['pendingAssessments' => $pendingAssessments])->layout('layouts.app');
    }
}
