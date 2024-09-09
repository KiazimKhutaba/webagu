<?php

namespace App\Modules\Lecture\Models;

use App\Models\User;
use App\Modules\File\Models\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Lecture
 *
 * @property int $id
 * @property string $title
 * @property string $excerpt
 * @property string $content
 * @property bool $is_visible
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\File\Models\File> $files
 * @property-read int|null $files_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Lecture newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lecture newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lecture query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lecture whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lecture whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lecture whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lecture whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lecture whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lecture whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lecture whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lecture whereUserId($value)
 * @mixin \Eloquent
 */
class Lecture extends Model
{
    use HasFactory;

    protected $table = 'lectures';

    protected $fillable = [
        'title',
        'excerpt',
        'content',
        'is_visible',
        'user_id'
    ];

    protected $casts = [
        'is_visible' => 'boolean'
    ];

    public array $files = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'lecture_id', 'id');
    }
}
