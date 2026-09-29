<?php

namespace App\Services;

use App\Models\Assessment;
use App\Enums\ComplianceStatus;
use App\Enums\AssessmentLevel;

class AssessmentScoringService
{
    public function calculateScore(Assessment $assessment): void
    {
        $items = $assessment->items()->with('criterion')->get();
        
        $totalEarned = 0;
        $totalPossible = 100; // Base percentage
        $hasMandatoryFailure = false;
        
        foreach ($items as $item) {
            $criterion = $item->criterion;
            $weight = $criterion->weight;
            
            if ($item->status === ComplianceStatus::NA) {
                // EXCLUDE
                $totalPossible -= $weight;
                continue;
            }
            
            if ($item->status === ComplianceStatus::COMPLY) {
                $totalEarned += $weight;
            } elseif ($item->status === ComplianceStatus::PARTIAL) {
                $totalEarned += ($weight * 0.5); // 50%
            } elseif ($item->status === ComplianceStatus::NON_COMPLY) {
                // 0 points, check if mandatory
                if ($criterion->is_mandatory) {
                    $hasMandatoryFailure = true;
                }
            }
        }
        
        $score = $totalPossible > 0 ? ($totalEarned / $totalPossible) * 100 : 0;
        
        $level = AssessmentLevel::NOT_ELIGIBLE;
        
        if (!$hasMandatoryFailure) {
            if ($score >= 90) {
                $level = AssessmentLevel::GOLD;
            } elseif ($score >= 80) {
                $level = AssessmentLevel::SILVER;
            } elseif ($score >= 70) {
                $level = AssessmentLevel::BRONZE;
            }
        }
        
        $assessment->update([
            'total_score' => $score,
            'has_mandatory_failure' => $hasMandatoryFailure,
            'level' => $level,
        ]);
        
        // Sync to User Tier
        if ($assessment->driver_id) {
            $assessment->driver->update([
                'tier' => $level->value
            ]);
        }
    }
}
