<?php

namespace App\Enums;

enum CaStatus: string
{
    case OPEN = 'OPEN';
    case IN_PROGRESS = 'IN PROGRESS';
    case CLOSED = 'CLOSED';
}
