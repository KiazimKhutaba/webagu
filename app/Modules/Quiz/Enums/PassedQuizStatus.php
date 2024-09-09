<?php

namespace App\Modules\Quiz\Enums;

enum PassedQuizStatus: string
{
    case Waiting = 'waiting';
    case OnReview = 'on_review';
    case Reviewed = 'reviewed';
}
