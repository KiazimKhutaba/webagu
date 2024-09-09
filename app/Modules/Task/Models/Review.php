<?php

namespace App\Modules\Task\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Task\Review
 *
 * @property int $id
 * @property string $status
 * @property string|null $message
 * @property int $reply_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Review newModelQuery()
 * @method static Builder|Review newQuery()
 * @method static Builder|Review query()
 * @method static Builder|Review whereCreatedAt($value)
 * @method static Builder|Review whereId($value)
 * @method static Builder|Review whereMessage($value)
 * @method static Builder|Review whereReplyId($value)
 * @method static Builder|Review whereStatus($value)
 * @method static Builder|Review whereUpdatedAt($value)
 * @property int $task_id
 * @property string $review_status
 * @property int $student_id student's that sends decision for review
 * @property int $reviewer_id review author
 * @method static Builder|Review whereReviewStatus($value)
 * @method static Builder|Review whereReviewerId($value)
 * @method static Builder|Review whereStudentId($value)
 * @method static Builder|Review whereTaskId($value)
 * @property int $lecture_id
 * @method static Builder|Review whereLectureId($value)
 * @mixin \Eloquent
 */
class Review extends Model
{
    use HasFactory;

    protected $table = 'task_reviews';

    protected $fillable = [
        'lecture_id',
        'task_id',
        'review_status',
        'student_id',
        'reviewer_id',
    ];
}
