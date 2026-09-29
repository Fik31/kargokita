<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Assessment;

class HseVerificationList extends Component
{
    public function render()
    {
        $pendingAssessments = Assessment::with('driver')->where('status', \App\Enums\AssessmentStatus::SUBMITTED)->latest()->get();
        return view('livewire.hse-verification-list', ['pendingAssessments' => $pendingAssessments]);
    }
}
