<?php

namespace App\Common\Enums;

use App\Common\Traits\EnumCasesToString;

enum FileableType : string
{
    use EnumCasesToString;

    case TaskUserReplyFile = 'task.user_reply_file';
    case Task = 'task';
    case TaskHistory = 'task_history.file';
    case LectureFile = 'lecture.file';
    case AccountAvatar = 'account.avatar';

    case QuizQuiz = 'quiz.quiz';
    case QuizQuestion = 'quiz.question';
    case QuizTask = 'quiz.task';
}
