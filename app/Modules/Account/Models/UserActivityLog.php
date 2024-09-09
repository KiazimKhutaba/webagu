<?php

namespace App\Modules\Account\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserActivityLog
 *
 * @method static Builder|UserActivityLog newModelQuery()
 * @method static Builder|UserActivityLog newQuery()
 * @method static Builder|UserActivityLog query()
 * @property int $id
 * @property int $user_id
 * @property string $action_name
 * @property string|null $os
 * @property string|null $browser
 * @property string|null $client_ip
 * @property string|null $url
 * @property string|null $referer
 * @property array|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $user_agent
 * @method static Builder|UserActivityLog whereActionName($value)
 * @method static Builder|UserActivityLog whereBrowser($value)
 * @method static Builder|UserActivityLog whereClientIp($value)
 * @method static Builder|UserActivityLog whereCreatedAt($value)
 * @method static Builder|UserActivityLog whereData($value)
 * @method static Builder|UserActivityLog whereId($value)
 * @method static Builder|UserActivityLog whereOs($value)
 * @method static Builder|UserActivityLog whereReferer($value)
 * @method static Builder|UserActivityLog whereUpdatedAt($value)
 * @method static Builder|UserActivityLog whereUrl($value)
 * @method static Builder|UserActivityLog whereUserAgent($value)
 * @method static Builder|UserActivityLog whereUserId($value)
 * @mixin Eloquent
 */
class UserActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action_name',
        'os',
        'browser',
        'user_agent',
        'client_ip',
        'url',
        'referer',
        'data',
    ];

    protected $casts = [
        'data' => 'array'
    ];

}
