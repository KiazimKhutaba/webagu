<?php

namespace App\Modules\Account\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\PasswordChange
 *
 * @method static Builder|PasswordChange newModelQuery()
 * @method static Builder|PasswordChange newQuery()
 * @method static Builder|PasswordChange query()
 * @property int $id
 * @property string $username
 * @property string $phone
 * @property string $password
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|PasswordChange whereCreatedAt($value)
 * @method static Builder|PasswordChange whereId($value)
 * @method static Builder|PasswordChange wherePassword($value)
 * @method static Builder|PasswordChange wherePhone($value)
 * @method static Builder|PasswordChange whereUpdatedAt($value)
 * @method static Builder|PasswordChange whereUsername($value)
 * @property bool $is_approved
 * @method static Builder|PasswordChange whereIsApproved($value)
 * @mixin \Eloquent
 */
class PasswordChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'password',
        'is_approved'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];


    protected $casts = [
        'is_approved' => 'boolean'
    ];
}
