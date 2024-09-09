<?php

namespace App\Modules\Post\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ViewsCount
 *
 * @method static Builder|ViewsCount newModelQuery()
 * @method static Builder|ViewsCount newQuery()
 * @method static Builder|ViewsCount query()
 * @property int $id
 * @property int $user_id
 * @property string $viewed_id
 * @property string $viewed_type
 * @property int $count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|ViewsCount whereCount($value)
 * @method static Builder|ViewsCount whereCreatedAt($value)
 * @method static Builder|ViewsCount whereId($value)
 * @method static Builder|ViewsCount whereUpdatedAt($value)
 * @method static Builder|ViewsCount whereUserId($value)
 * @method static Builder|ViewsCount whereViewedId($value)
 * @method static Builder|ViewsCount whereViewedType($value)
 * @mixin \Eloquent
 */
class ViewsCount extends Model
{
    use HasFactory;

    protected $table = 'views_count';

    protected $fillable = [
        'viewed_type',
        'viewed_id',
        'count',
        'user_id',
    ];
}
