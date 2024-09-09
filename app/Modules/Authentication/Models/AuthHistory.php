<?php

namespace App\Modules\Authentication\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AuthHistory
 *
 * @property int $id
 * @property int $user_id
 * @property string $action_type
 * @property string|null $client_ip
 * @property string|null $user_agent
 * @property string|null $device_os
 * @property string|null $user_agent2
 * @property string|null $referer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AuthHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthHistory whereActionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthHistory whereClientIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthHistory whereDeviceOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthHistory whereReferer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthHistory whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthHistory whereUserAgent2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthHistory whereUserId($value)
 * @mixin \Eloquent
 */
class AuthHistory extends Model
{
    use HasFactory;

    protected $table = 'auth_history';

    protected $fillable = [
        'user_id',
        'action_type',
        'client_ip',
        'user_agent',
        'device_os',
        'user_agent2', //  Sec-Ch-Ua
        'referer',
    ];
}
