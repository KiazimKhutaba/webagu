<?php

namespace App\Modules\Task\Models;

use App\Models\User;
use App\Modules\File\Models\File;
use App\Modules\Task\Enums\CommentableType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Comment
 *
 * @property int $id
 * @property string $commentable_type
 * @property int $commentable_id
 * @property string $message
 * @property mixed|null $meta
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\File> $files
 * @property-read int|null $files_count
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereCommentableId($value)
 * @method static Builder|Comment whereCommentableType($value)
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereMessage($value)
 * @method static Builder|Comment whereMeta($value)
 * @method static Builder|Comment whereUpdatedAt($value)
 * @method static Builder|Comment whereUserId($value)
 * @property int $sender_id comment sender user_id
 * @property int $receiver_id comment receiver user_id
 * @property-read \App\Models\User|null $user
 * @method static Builder|Comment whereReceiverId($value)
 * @method static Builder|Comment whereSenderId($value)
 * @mixin \Eloquent
 */
class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'commentable_type',
        'commentable_id',
        'message'
    ];

    public function files(CommentableType $commentableType = CommentableType::TaskHistory): HasMany
    {
        return $this->hasMany(File::class, 'fileable_id', 'id')->where(['fileable_type' => $commentableType->value]);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }
}
