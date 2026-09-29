<?php

namespace App\Enums;

enum ComplianceStatus: string
{
    case COMPLY = 'COMPLY';
    case NON_COMPLY = 'NON-COMPLY';
    case NA = 'N/A';
}
