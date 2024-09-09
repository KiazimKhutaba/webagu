<?php

namespace App\Modules\File\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\File
 *
 * @property int $id
 * @property int $user_id
 * @property string $original_name
 * @property string $generated_name
 * @property string $type
 * @property string $size
 * @property string|null $object_source
 * @property int|null $object_source_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static Builder|File newModelQuery()
 * @method static Builder|File newQuery()
 * @method static Builder|File query()
 * @method static Builder|File whereCreatedAt($value)
 * @method static Builder|File whereGeneratedName($value)
 * @method static Builder|File whereId($value)
 * @method static Builder|File whereObjectSource($value)
 * @method static Builder|File whereObjectSourceId($value)
 * @method static Builder|File whereOriginalName($value)
 * @method static Builder|File whereSize($value)
 * @method static Builder|File whereType($value)
 * @method static Builder|File whereUpdatedAt($value)
 * @method static Builder|File whereUserId($value)
 * @property string|null $fileable_type
 * @property int|null $fileable_id
 * @method static Builder|File whereFileableId($value)
 * @method static Builder|File whereFileableType($value)
 * @mixin \Eloquent
 */
class File extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = [
        'user_id',
        'original_name',
        'generated_name',
        'type',
        'size',
        'fileable_type',
        'fileable_id',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
