<?php

namespace App\Modules\Authentication\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Modules\Authentication\Models\Auth
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Auth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Auth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Auth query()
 * @mixin \Eloquent
 */
class Auth extends Model
{
    use HasFactory;

    protected $fillable = [
        '',
    ];
}
