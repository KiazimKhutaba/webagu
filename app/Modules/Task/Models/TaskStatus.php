<?php

namespace App\Modules\Task\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Modules\Task\Models\TaskStatus
 *
 * @property int $id
 * @property int $task_id
 * @property int $student_id
 * @property int $teacher_id
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|TaskStatus newModelQuery()
 * @method static Builder|TaskStatus newQuery()
 * @method static Builder|TaskStatus query()
 * @method static Builder|TaskStatus whereCreatedAt($value)
 * @method static Builder|TaskStatus whereId($value)
 * @method static Builder|TaskStatus whereStatus($value)
 * @method static Builder|TaskStatus whereStudentId($value)
 * @method static Builder|TaskStatus whereTaskId($value)
 * @method static Builder|TaskStatus whereTeacherId($value)
 * @method static Builder|TaskStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TaskStatus extends Model
{
    use HasFactory;

    protected $table = 'task_statuses';

    protected $fillable = [
        'task_id',
        'student_id',
        'teacher_id',
        'status',
    ];
}
