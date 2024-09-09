<?php

namespace App\Modules\Quiz\Enums;

use App\Common\Traits\EnumCasesToString;

enum QuizFileableType : string
{
    use EnumCasesToString;

    case QuizQuiz = 'quiz.quiz';
    case QuizQuestion = 'quiz.question';
    case QuizTask = 'quiz.task';
}
