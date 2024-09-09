<?php

namespace App\Modules\Question\Repositories;

use App\Modules\Question\Models\Question;

class QuestionsRepository
{
    public function export()
    {
        return Question::all(['title', 'theme', 'type', 'content', 'solution']);
        // return json_encode($questions, JSON_PRETTY_PRINT);
    }
}
