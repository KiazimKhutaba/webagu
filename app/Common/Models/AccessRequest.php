<?php

namespace App\Common\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Request access to specific resource
 *
 * @method static Builder|AccessRequest newModelQuery()
 * @method static Builder|AccessRequest newQuery()
 * @method static Builder|AccessRequest query()
 * @mixin \Eloquent
 */
class AccessRequest extends Model
{
    use HasFactory;

    protected $table = 'access_requests';

    protected $fillable = [
        'user_id',
        'granted',
        'accessible_id',
        'accessible_type',
    ];

    protected $casts = [
      'granted' => 'bool'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
