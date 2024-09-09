<?php

namespace App\Modules\Task\Enums;

use App\Common\Traits\EnumCasesToString;

enum TaskStatus: string
{
    use EnumCasesToString;

    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Returned = 'returned';
    case WaitingForReview = 'wait_for_review';
    case AwaitingExecution = 'awaiting_execution';
}
