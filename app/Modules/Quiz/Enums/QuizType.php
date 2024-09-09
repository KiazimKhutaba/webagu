<?php

namespace App\Modules\Quiz\Enums;

use App\Common\Traits\EnumCasesToString;

enum QuizType: string
{
    use EnumCasesToString;

    case Training = 'training';
    case Seminar = 'seminar';
    case Midterm = 'midterm';
}
