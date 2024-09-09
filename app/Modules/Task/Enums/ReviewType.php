<?php

namespace App\Modules\Task\Enums;


enum ReviewType: string
{
    case Accepted = "accepted";
    case Rejected = "rejected";
    case Returned = "returned";
    case WaitingForReview = "wait_for_review";
    case AwaitingExecution = "awaiting_execution";
}
