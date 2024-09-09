<?php

namespace App\Modules\OpenForms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\OpenForms\FormQuiz
 *
 * @property string $id
 * @property string $title
 * @property int $lecture_id
 * @property string|null $description
 * @property int $duration
 * @property int $is_available
 * @property int $shuffle_questions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuiz query()
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuiz whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuiz whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuiz whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuiz whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuiz whereIsAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuiz whereLectureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuiz whereShuffleQuestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuiz whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuiz whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\OpenForms\Models\FormQuestion> $questions
 * @property-read int|null $questions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\OpenForms\Models\QuestionOption> $options
 * @property-read int|null $options_count
 * @mixin \Eloquent
 */
class FormQuiz extends Model
{
    protected $table = "of_quizzes";

    protected $keyType = "string";

    protected $fillable = [
        "id",
        "title",
        "lecture_id",
        "description",
        "duration",
        "is_available",
        "shuffle_questions",
    ];

    protected $casts = [
        "is_available" => "boolean",
        "shuffle_questions" => "boolean",
    ];


    public function questions(): HasMany {
        return $this->hasMany(FormQuestion::class, 'quiz_id', 'id');
    }

    public function options(): HasMany {
        return $this->hasMany(QuestionOption::class, 'quiz_id', 'id');
    }

}
