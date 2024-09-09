<?php

namespace App\Modules\Task\Enums;

enum CommentableType : string
{
    case Task = 'task';
    case TaskHistory = 'task_history'; // task_history.file
}
