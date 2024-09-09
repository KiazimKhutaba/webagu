<?php

namespace App\Modules\File\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ImageMeta
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ImageMeta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageMeta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageMeta query()
 * @property int $id
 * @property string $generated_name
 * @property int $user_id image owner
 * @property string $dest avatar or another type
 * @property array|null $exif
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ImageMeta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageMeta whereDest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageMeta whereExif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageMeta whereGeneratedName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageMeta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageMeta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageMeta whereUserId($value)
 * @mixin \Eloquent
 */
class ImageMeta extends Model
{
    use HasFactory;


    protected $fillable = [
        'generated_name',
        'user_id',
        'dest',
        'exif',
    ];

    protected $casts = [
        'exif' => 'array'
    ];
}
