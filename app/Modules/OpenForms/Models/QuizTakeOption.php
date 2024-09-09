<?php

namespace App\Modules\OpenForms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\OpenForms\QuizTakeOption - Question option selected by examinee
 *
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTakeOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTakeOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTakeOption query()
 * @property string $id
 * @property string $quiztake_id
 * @property string $quiz_id
 * @property string $question_id
 * @property string|null $option_id
 * @property string $type
 * @property array $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTakeOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTakeOption whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTakeOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTakeOption whereOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTakeOption whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTakeOption whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTakeOption whereQuiztakeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTakeOption whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTakeOption whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuizTakeOption extends Model
{
    use HasFactory;

    protected $table = "of_quiztake_options";

    protected $keyType = "string";

    protected $fillable = [
        'id',
        'quiztake_id',
        'quiz_id',
        'question_id',
        'option_id',
        'type',
        'data',
    ];

    protected $casts = [
        'data' => 'array'
    ];


}
