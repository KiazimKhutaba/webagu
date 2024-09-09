<?php

namespace App\Modules\Task\Models;

use App\Common\Enums\FileableType;
use App\Common\Enums\UserRole;
use App\Models\User;
use App\Modules\File\Models\File;
use App\Modules\Lecture\Models\Lecture;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

/**
 * App\Modules\Task\Models\Task
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property bool $is_visible
 * @property int $lecture_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $expired_at
 * @property bool $is_seminar
 * @property-read Collection<int, TaskHistory> $history
 * @property-read int|null $history_count
 * @method static Builder|Task newModelQuery()
 * @method static Builder|Task newQuery()
 * @method static Builder|Task query()
 * @method static Builder|Task whereCreatedAt($value)
 * @method static Builder|Task whereDescription($value)
 * @method static Builder|Task whereExpiredAt($value)
 * @method static Builder|Task whereId($value)
 * @method static Builder|Task whereIsSeminar($value)
 * @method static Builder|Task whereIsVisible($value)
 * @method static Builder|Task whereLectureId($value)
 * @method static Builder|Task whereTitle($value)
 * @method static Builder|Task whereUpdatedAt($value)
 * @method static Builder|Task whereUserId($value)
 * @property-read Collection<int, File> $files
 * @property-read int|null $files_count
 * @property-read Collection<int, User> $students
 * @property-read int|null $students_count
 * @property-read Lecture|null $lecture
 * @mixin \Eloquent
 */
class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'description',
        'is_visible',
        'is_seminar',
        'lecture_id',
        'user_id',
        'expired_at'
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'is_seminar' => 'boolean',
        'expired_at' => 'datetime:Y-m-d\TH:i', // for datetime-local input type
    ];


    public function history(int $user_id): HasMany
    {
        return $this->hasMany(TaskHistory::class, 'task_id', 'id')
            ->where(function (Builder $builder) use ($user_id) {
                $builder->where(['sender_id' => $user_id, 'receiver_id' => $this->user_id])->orWhere(function (Builder $query) use ($user_id) {
                    $query->where(['sender_id' => $this->user_id, 'receiver_id' => $user_id]);
                });
            })
            ->with(['files', 'user']);
    }

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class, TaskHistory::class,
            'task_id',
            'id',
            'id',
            'sender_id'
        )
            ->distinct()
            ->where('users.role', UserRole::Student)
            ->select(['users.id', 'users.firstname', 'users.lastname', 'users.role']);
    }


    /*public function submittedTasks(?int $task_id = null): HasMany
    {
        return $this->hasMany(TaskHistory::class, 'task_id', 'id');
    }*/

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'fileable_id', 'id')->where(['fileable_type' => FileableType::Task]);
    }

    public function lecture(): BelongsTo
    {
        return $this->belongsTo(Lecture::class, 'lecture_id', 'id')->select(['id', 'title']);
    }
}
