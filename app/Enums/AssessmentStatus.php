<?php

namespace App\Enums;

enum AssessmentStatus: string
{
    case DRAFT = 'DRAFT';
    case SUBMITTED = 'SUBMITTED';
    case VERIFIED = 'VERIFIED';
    case CLOSED = 'CLOSED';
}
