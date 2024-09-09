<?php

namespace App\Modules\OpenForms\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OpenForms\QuestionOption
 *
 * @property string $id
 * @property string $question_id
 * @property string|null $text
 * @property int $is_checked
 * @property string $control_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionOption whereControlType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionOption whereIsChecked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionOption whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionOption whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionOption whereUpdatedAt($value)
 * @property string $quiz_id
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionOption whereQuizId($value)
 * @mixin \Eloquent
 */
class QuestionOption extends Model
{
    protected $table = "of_question_options";

    protected $keyType = "string";

    protected $fillable = [
        "id",
        "question_id",
        "quiz_id",
        "text",
        "is_checked",
        "control_type",
    ];

    protected $casts = [
        "is_checked" => "boolean",
    ];
}
