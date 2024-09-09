<?php

namespace App\Modules\Quiz\Models;

use App\Common\Enums\AccesibleType;
use App\Common\Models\AccessRequest;
use App\Modules\Lecture\Models\Lecture;
use App\Modules\Question\Models\Question;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use function count;

/**
 * App\Models\Quiz
 *
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property int $number_of_questions
 * @property int $number_of_tasks
 * @property mixed $themes Lectures ids
 * @property int $is_available
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Quiz newModelQuery()
 * @method static Builder|Quiz newQuery()
 * @method static Builder|Quiz query()
 * @method static Builder|Quiz whereCreatedAt($value)
 * @method static Builder|Quiz whereId($value)
 * @method static Builder|Quiz whereIsAvailable($value)
 * @method static Builder|Quiz whereNumberOfQuestions($value)
 * @method static Builder|Quiz whereNumberOfTasks($value)
 * @method static Builder|Quiz whereThemes($value)
 * @method static Builder|Quiz whereTitle($value)
 * @method static Builder|Quiz whereUpdatedAt($value)
 * @method static Builder|Quiz whereUuid($value)
 * @property array $theme_names
 * @method static Builder|Quiz whereThemeNames($value)
 * @property array<int> $assigned_to User/users (user_id) for whom this test created, or zero for all
 * @method static Builder|Quiz whereAssignedTo($value)
 * @property int $can_regenerate
 * @method static Builder|Quiz whereCanRegenerate($value)
 * @property int $number_of_quizzes
 * @method static Builder|Quiz whereNumberOfQuizzes($value)
 * @property int|null $is_midterm
 * @method static Builder|Quiz whereIsMidterm($value)
 * @mixin Eloquent
 */
class Quiz extends Model
{
    use HasFactory;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';


    protected $fillable = [
        'uuid',
        'title',
        'number_of_questions',
        'number_of_tasks',
        'number_of_quizzes',
        'themes',
        'theme_names',
        'is_available',
        'can_regenerate',
        'assigned_to',
        'is_midterm',
        'type',
    ];

    protected $casts = [
        'themes' => 'array',
        'theme_names' => 'array',
        'assigned_to' => 'array',
        'can_regenerate' => 'bool'
    ];


    public function availableForAll()
    {
        return (count($this->assigned_to) === 1) && ($this->assigned_to[0] === 0);
    }

    public function availableFor(int $user_id)
    {
        return in_array($user_id, $this->assigned_to);
    }

    public function accessRequest(): HasOne
    {
        return $this->hasOne(AccessRequest::class, 'accessible_id', 'id')
            ->where(['accessible_type' => AccesibleType::Quiz])
            ->select(['id', 'granted', 'accessible_id']);
    }

    public function generateMidtermQuiz(array $themes = [1, 2, 3, 4, 5, 6, 7, 8], int $questions_per_theme = 4, int $tasks_per_theme = 2): array
    {
        $themesClause = rtrim(str_repeat('?,', count($themes)), ',');

        $sql2 = "
            WITH RankedQuestions AS (
                SELECT
                    *,
                    ROW_NUMBER() OVER (PARTITION BY theme ORDER BY RAND()) AS row_num
                FROM questions
                WHERE theme in ($themesClause) and type != 'quiz'
            )
            SELECT *
            FROM RankedQuestions
            WHERE row_num <= ?
        ";

        $sql = "
            SELECT *
            FROM (WITH random_questions AS (SELECT *,
                                                  ROW_NUMBER() OVER (PARTITION BY theme ORDER BY RAND()) AS row_num
                                           FROM questions
                                           WHERE theme in ($themesClause)
                                             AND is_visible = 1
                                             AND type = 'question'),
                       random_tasks AS (SELECT *,
                                              ROW_NUMBER() OVER (PARTITION BY theme ORDER BY RAND()) AS row_num
                                       FROM questions
                                       WHERE theme in ($themesClause)
                                         AND is_visible = 1
                                         AND type = 'task')
                  SELECT *
                  FROM random_questions
                  WHERE row_num <= ?

                  UNION ALL

                  SELECT *
                  FROM random_tasks
                  WHERE row_num <= ?) as random_quiz

            ORDER BY random_quiz.theme
        ";

        // throw new \Exception($sql);

        return DB::select($sql, [...$themes, ...$themes, $questions_per_theme, $tasks_per_theme]);
    }

    /**
     * @throws \Exception
     */
    public function generateRandomQuestions(int $user_id)
    {
        $seeder1 = random_int(1024, 2048);
        $seeder2 = random_int(2049, 4096);
        $seeder3 = random_int(4097, 8100);

        // Todo: refactor to one sql query
        $q1 = Question::inRandomOrder($seeder1)->whereIn('theme', $this->themes);
        $q2 = Question::inRandomOrder($seeder2)->whereIn('theme', $this->themes);
        $q3 = Question::inRandomOrder($seeder3)->whereIn('theme', $this->themes);

        $randomQuestions = $q1->where(['type' => 'question'])
                            ->limit($this->number_of_questions)
                            ->orderBy('id')
                            ->get();
                            //->shuffle(time());
                            //->pluck('id');

        $randomTasks = $q2->where(['type' => 'task'])
                        ->limit($this->number_of_tasks)
                        ->orderBy('id')
                        ->get();
                        // ->shuffle(time());
                        //->pluck('id');

        $randomQuizzes = $q3->where(['type' => 'quiz'])
                        ->limit($this->number_of_quizzes)
                        ->orderBy('id')
                        ->get();
            // ->shuffle(time());
        //->pluck('id');

        return [
            'questions' => $randomQuestions->toArray(),
            'tasks' => $randomTasks->toArray(),
            'quizzes' => $randomQuizzes->toArray(),
            'questions_ids' => $randomQuestions->pluck('id'),
            'tasks_ids' => $randomTasks->pluck('id'),
            'quizzes_ids' => $randomQuizzes->pluck('id'),
        ];
    }

    public function themeNames()
    {
        return Lecture::whereIn('id', $this->themes)->get(['id', 'title']);
    }

    public function items()
    {
        return Question::whereIn('theme', $this->themes)->get();
    }


    public function questions(): array|Collection|\Illuminate\Support\Collection
    {
        return Question::where(['type' => 'question'])->whereIn('theme', $this->themes)->get();
    }

    public function tasks(): array|Collection|\Illuminate\Support\Collection
    {
        return Question::where(['type' => 'task'])->whereIn('theme', $this->themes)->get();
    }

    public function quizzes(): array|Collection|\Illuminate\Support\Collection
    {
        return Question::where(['type' => 'quiz'])->whereIn('theme', $this->themes)->get();
    }
}
