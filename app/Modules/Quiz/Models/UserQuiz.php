<?php

namespace App\Modules\Quiz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserQuiz
 *
 * @property int $id
 * @property int $quiz_id foreign key to quiz table
 * @property int $user_id foreign key to users table
 * @property array $tasks
 * @property array $questions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserQuiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserQuiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserQuiz query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserQuiz whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserQuiz whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserQuiz whereQuestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserQuiz whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserQuiz whereTasks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserQuiz whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserQuiz whereUserId($value)
 * @mixin \Eloquent
 */
class UserQuiz extends Model
{
    use HasFactory;

    protected $fillable = [
        /*'user_id' => '',
        'quiz_id' => '',*/
        'tasks' => '',
        'questions' => ''
    ];


    protected $casts = [
        'tasks' => 'array',
        'questions' => 'array'
    ];
}
