<?php

namespace App\Enums;

enum AssessmentLevel: string
{
    case GOLD = 'GOLD';
    case SILVER = 'SILVER';
    case BRONZE = 'BRONZE';
    case NOT_ELIGIBLE = 'NOT ELIGIBLE';
}
