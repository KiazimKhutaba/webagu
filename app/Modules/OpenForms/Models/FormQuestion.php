<?php

namespace App\Modules\OpenForms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\OpenForms\FormQuestion
 *
 * @property string $id
 * @property string $quiz_id
 * @property string $title
 * @property string|null $image
 * @property string $variant_type
 * @property int $has_description
 * @property int $points
 * @property int $required
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuestion whereHasDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuestion whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuestion wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuestion whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuestion whereRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuestion whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuestion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuestion whereVariantType($value)
 * @property string|null $description
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\OpenForms\Models\QuestionOption> $options
 * @property-read int|null $options_count
 * @method static \Illuminate\Database\Eloquent\Builder|FormQuestion whereDescription($value)
 * @mixin \Eloquent
 */
class FormQuestion extends Model
{
    protected $table = "of_questions";

    protected $keyType = "string";

    protected $fillable = [
        "id",
        "quiz_id",
        "title",
        "image",
        "variant_type",
        "has_description",
        "description",
        "points",
        "required",
    ];

    protected $casts = [
        "required" => "boolean"
    ];

    public function options(): HasMany {
        return $this->hasMany(QuestionOption::class, 'question_id', 'id');
    }
}
