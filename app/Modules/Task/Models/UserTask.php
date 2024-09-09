<?php

namespace App\Modules\Task\Models;

use App\Modules\File\Models\File;
use App\Modules\Task\Enums\CommentableType;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\UserTask
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $is_visible
 * @property int $lecture_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserTask newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserTask newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserTask query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserTask whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTask whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTask whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTask whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTask whereLectureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTask whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTask whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserTask whereUserId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, File> $files
 * @property-read int|null $files_count
 * @property string|null $expired_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserTask whereExpiredAt($value)
 * @property bool $is_seminar
 * @method static \Illuminate\Database\Eloquent\Builder|UserTask whereIsSeminar($value)
 * @mixin \Eloquent
 */
class UserTask extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'description',
        'is_visible',
        'is_seminar',
        'can_regenerate',
        'lecture_id',
        'user_id',
        'expired_at'
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'is_seminar' => 'boolean',
        'expired_at' => 'datetime:Y-m-d\TH:i', // for datetime-local input type
    ];


    protected function expiredAt(): Attribute
    {
        return Attribute::make(
            //get: fn(int $value) => $value,
            set: fn(int $value) => Carbon::createFromTimestamp($value / 1000)
        );
    }

    public static function getAll(int $user_id, bool $trunc_description = true)
    {
        return self::leftJoin('task_reviews', function (JoinClause $joinClause) use ($user_id) {
            $joinClause->on('tasks.id', '=', 'task_reviews.task_id');
            $joinClause->on('task_reviews.student_id', '=', DB::raw($user_id));
        })
            ->leftJoin('lectures', 'tasks.lecture_id', '=', 'lectures.id')
            ->select([
                // 'tasks.*',
                'tasks.id',
                'tasks.title',
                // $trunc_description ? DB::raw('substring(tasks.description, 0, 140) as description') : 'tasks.description',
                //'tasks.description',
                'tasks.is_visible',
                'tasks.is_seminar',
                'tasks.lecture_id',
                'tasks.user_id',
                'tasks.created_at',
                'tasks.updated_at',
                'task_reviews.review_status',
                'lectures.title as lecture_title'
            ]);
        //->get();
    }


    public function withReview(int $user_id)
    {
        return self::leftJoin('task_reviews', function (JoinClause $joinClause) use ($user_id) {
            $joinClause->on('tasks.id', '=', 'task_reviews.task_id');
            $joinClause->on('task_reviews.student_id', '=', DB::raw($user_id));
        })
            ->where(['tasks.id' => $this->id])
            ->select('tasks.*', 'task_reviews.review_status')
            ->first();

        /**
         *select t.*, tr.review_status
         * from tasks t left join task_reviews tr on t.id = tr.task_id and tr.student_id = 14
         * where t.id = 42
         */
    }


    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'fileable_id', 'id')->where(['fileable_type' => CommentableType::Task]);
    }


    public function comments(int $for_user): HasMany
    {
        return $this->hasMany(Comment::class, 'commentable_id', 'id')
            ->where(['commentable_type' => CommentableType::TaskHistory])
            ->where(function (Builder $builder) use ($for_user) {
                $builder->where(['sender_id' => $for_user, 'receiver_id' => $this->user_id])
                    ->orWhere(function (Builder $query) use ($for_user) {
                        $query->where(['sender_id' => $this->user_id, 'receiver_id' => $for_user]);
                    });
            });
    }




    public function allReviewedTasks()
    {

    }

}
