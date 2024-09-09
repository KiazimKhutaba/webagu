<?php

namespace App\Modules\Question\Models;

use App\Modules\Lecture\Models\Lecture;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Question
 *
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property string|null $content
 * @property string $type
 * @property int $theme foreign key to lectures
 * @property string|null $solution
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Lecture|null $_theme
 * @method static Builder|Question newModelQuery()
 * @method static Builder|Question newQuery()
 * @method static Builder|Question query()
 * @method static Builder|Question whereContent($value)
 * @method static Builder|Question whereCreatedAt($value)
 * @method static Builder|Question whereId($value)
 * @method static Builder|Question whereSolution($value)
 * @method static Builder|Question whereTheme($value)
 * @method static Builder|Question whereTitle($value)
 * @method static Builder|Question whereType($value)
 * @method static Builder|Question whereUpdatedAt($value)
 * @method static Builder|Question whereUuid($value)
 * @property bool $is_visible
 * @method static Builder|Question whereIsVisible($value)
 * @mixin \Eloquent
 */
class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'content',
        'type',
        'theme',
        'solution',
        'is_visible',
    ];


    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function _theme(): BelongsTo
    {
        return $this->belongsTo(Lecture::class, 'theme', 'id')->select(['id', 'title']);
    }
}
