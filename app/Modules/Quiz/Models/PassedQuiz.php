<?php

namespace App\Modules\Quiz\Models;

use App\Models\User;
use App\Modules\File\Models\File;
use App\Modules\Question\Models\Question;
use App\Modules\Quiz\Enums\QuizFileableType;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\PassedQuiz
 *
 * @property int $id
 * @property int $quiz_id
 * @property string $quiz_uuid
 * @property int $student_id
 * @property array $quiz
 * @property array $questions
 * @property array $tasks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $student
 * @method static Builder|PassedQuiz newModelQuery()
 * @method static Builder|PassedQuiz newQuery()
 * @method static Builder|PassedQuiz query()
 * @method static Builder|PassedQuiz whereCreatedAt($value)
 * @method static Builder|PassedQuiz whereId($value)
 * @method static Builder|PassedQuiz whereQuestions($value)
 * @method static Builder|PassedQuiz whereQuiz($value)
 * @method static Builder|PassedQuiz whereQuizId($value)
 * @method static Builder|PassedQuiz whereQuizUuid($value)
 * @method static Builder|PassedQuiz whereStudentId($value)
 * @method static Builder|PassedQuiz whereTasks($value)
 * @method static Builder|PassedQuiz whereUpdatedAt($value)
 * @property string $quiz_title
 * @method static Builder|PassedQuiz whereQuizTitle($value)
 * @property int|null $pass_count
 * @method static Builder|PassedQuiz wherePassCount($value)
 * @property array $quizzes
 * @method static Builder|PassedQuiz whereQuizzes($value)
 * @mixin \Eloquent
 */
class PassedQuiz extends Model
{
    use HasFactory;

    protected $table = 'passed_quizzes';

    protected $fillable = [
        'quiz_id',
        'quiz_uuid',
        'student_id',
        'quiz_title',
        'questions',
        'tasks',
        'quizzes',
        'pass_count',
        'status'
    ];


    protected $casts = [
        'quiz' => 'array',
        'questions' => 'array',
        'tasks' => 'array',
        'quizzes' => 'array',
    ];


    private static function queryBuilder(): Builder|EloquentBuilder
    {
        return self::with(['replyFiles'])
            ->from('passed_quizzes as pq')
            ->leftJoin('users as u', 'u.id', '=', 'pq.student_id')
            ->leftJoin('quizzes as q', 'q.id', '=', 'pq.quiz_id')
            ->select([
                'pq.*',
                'q.theme_names',
                'u.lastname as student_lastname',
                'u.firstname as student_firstname',
                'u.department as student_department',
                'q.theme_names'
            ])
            ->orderBy('pq.created_at', 'desc');
    }


    private function decodeThemeNames($item)
    {
        $item->theme_names = json_decode($item->theme_names);
        return $item;
    }

    public function getPassedQuizBy(int $id)
    {
        $result = static::queryBuilder()->where(['pq.id' => $id])->first();
        $result->theme_names = json_decode($result->theme_names);

        return $result;
    }

    public function getPassedQuizzes()
    {
        $query = static::queryBuilder();

        $result = $query->paginate();
        $items = collect($result->items())->map($this->decodeThemeNames(...));
        $result->data = $items;

        return $result;
    }

    public function getQuiz()
    {
        return Quiz::where(['id' => $this->quiz_id])->first();
    }


    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'id');
    }


    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id')->select('id', 'firstname', 'lastname', 'department');
    }

    public function replyFiles(): HasMany
    {
        return $this->hasMany(File::class, 'fileable_id', 'id')
            ->whereIn('fileable_type', QuizFileableType::cases())
            ->select([
                'id',
                'fileable_id',
                'fileable_type',
                'generated_name',
                'type',
            ]);
    }

    public function getQiuzQuestions()
    {
        return Question::whereIn('id', [...$this->questions, ...$this->tasks, ...$this->quizzes])
                    ->orderBy('theme')
                    ->get()
                    ->groupBy('type');
    }

    public function getPassedQuiz()
    {
        return [
            'questions' => Question::whereIn('id', $this->questions)->orderBy('id')->get(),
            'tasks' => Question::whereIn('id', $this->tasks)->orderBy('id')->get(),
            'quizzes' => Question::whereIn('id', $this->quizzes)->orderBy('id')->get(),
        ];
    }


    public function getPassedMidtermQuiz()
    {
        return [
            'questions' => Question::whereIn('id', $this->questions)->orderBy('theme')->get(),
            'tasks' => Question::whereIn('id', $this->tasks)->orderBy('theme')->get(),
            'quizzes' => Question::whereIn('id', $this->quizzes)->orderBy('theme')->get(),
        ];
    }
}
