<?php

namespace App\Modules\Task\Models;

use App\Modules\File\Models\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Task\Reply
 *
 * @property int $id
 * @property string|null $description
 * @property int $task_id
 * @property int $user_id owner of this reply
 * @property int $lecture_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, File> $files
 * @property-read int|null $files_count
 * @method static \Illuminate\Database\Eloquent\Builder|Reply newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reply newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reply query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reply whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reply whereLectureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reply whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reply whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reply whereUserId($value)
 * @mixin \Eloquent
 */
class Reply extends Model
{
    use HasFactory;

    protected $table = 'task_replies';

    protected $fillable = [
        'user_id',
        'description',
        'task_id',
        'lecture_id'
    ];


    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'fileable_id', 'id')->where(['fileable_type' => Reply::class]);
    }
}
