<?php

namespace App\Modules\OpenForms\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\OpenForms\QuizTake
 *
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTake newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTake newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTake query()
 * @property string $id
 * @property string $quiz_id
 * @property int $user_id
 * @property Carbon|null $started_at
 * @property string|null $client_ip
 * @property string|null $user_agent
 * @property int $page_switches_count
 * @property mixed|null $geolocation
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTake whereClientIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTake whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTake whereGeolocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTake whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTake wherePageSwitchesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTake whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTake whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTake whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTake whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuizTake whereUserId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\OpenForms\Models\QuizTakeOption> $answers
 * @property-read int|null $answers_count
 * @property-read User|null $examinee
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\OpenForms\Models\FormQuestion> $questions
 * @property-read int|null $questions_count
 * @property-read \App\Modules\OpenForms\Models\FormQuiz|null $quiz
 * @mixin \Eloquent
 */
class QuizTake extends Model
{
    use HasFactory;

    protected $table = "of_quiztake";

    protected $keyType = "string";

    protected $fillable = [
        'id',
        'quiz_id',
        'user_id',
        'client_ip',
        'user_agent',
        'page_switches_count',
        'started_at',
        'page_switches_count',
        'geolocation',
    ];

    protected $casts = [
        'started_at' => 'datetime'
    ];


    public function quiz(): BelongsTo
    {
        return $this->belongsTo(FormQuiz::class, 'quiz_id', 'id')->select([
            'id',
            'title',
            'lecture_id',
            'description',
            'duration',
        ]);
    }

    public function quizWithQuestions()
    {
        return $this->quiz()->with('questions');
    }

    /**
     *
     * User who passed quiz
     *
     * @return BelongsTo
     */
    public function examinee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select([
            'id',
            'department',
            'avatar',
            DB::raw('concat(lastname, \' \', firstname) as full_name')
        ]);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(FormQuestion::class, 'quiz_id', 'quiz_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizTakeOption::class, 'quiztake_id', 'id');
    }

    protected function startedAt(): Attribute
    {
        return Attribute::make(
            //get: fn(int $value) => $value,
            set: fn(int $value) => Carbon::createFromTimestamp($value / 1000)
        );
    }

}
