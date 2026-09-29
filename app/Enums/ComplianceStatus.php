<?php

namespace App\Enums;

enum ComplianceStatus: string
{
    case COMPLY = 'COMPLY';
    case PARTIAL = 'PARTIAL';
    case NON_COMPLY = 'NON-COMPLY';
    case NA = 'N/A';
}
