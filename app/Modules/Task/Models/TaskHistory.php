<?php

namespace App\Modules\Task\Models;

use App\Common\Enums\FileableType;
use App\Models\User;
use App\Modules\File\Models\File;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Modules\Task\Models\TaskHistory
 *
 * @property int $id
 * @property int $task_id
 * @property int $student_id
 * @property string $status
 * @property int $lecture_id
 * @property int $teacher_id
 * @property string $text
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Task $task
 * @method static Builder|TaskHistory newModelQuery()
 * @method static Builder|TaskHistory newQuery()
 * @method static Builder|TaskHistory query()
 * @method static Builder|TaskHistory whereCreatedAt($value)
 * @method static Builder|TaskHistory whereId($value)
 * @method static Builder|TaskHistory whereLectureId($value)
 * @method static Builder|TaskHistory whereStatus($value)
 * @method static Builder|TaskHistory whereStudentId($value)
 * @method static Builder|TaskHistory whereTaskId($value)
 * @method static Builder|TaskHistory whereTeacherId($value)
 * @method static Builder|TaskHistory whereText($value)
 * @method static Builder|TaskHistory whereUpdatedAt($value)
 * @property int $sender_id
 * @property int $receiver_id
 * @property string|null $message
 * @property-read \Illuminate\Database\Eloquent\Collection<int, File> $files
 * @property-read int|null $files_count
 * @property-read User|null $user
 * @method static Builder|TaskHistory whereMessage($value)
 * @method static Builder|TaskHistory whereReceiverId($value)
 * @method static Builder|TaskHistory whereSenderId($value)
 * @mixin \Eloquent
 */
class TaskHistory extends Model
{
    protected $table = 'task_histories';

    protected $fillable = [
        'task_id',
        'sender_id',
        'receiver_id',
        'status',
        'message',
        'lecture_id',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'sender_id')->select(['id', 'firstname', 'lastname', 'role']);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'fileable_id', 'id')->where(['fileable_type' => FileableType::TaskHistory]);
    }
}
