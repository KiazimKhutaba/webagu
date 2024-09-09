<?php

namespace App\Modules\Task\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'lecture_id' => 'int',
            'task_id' => 'required|int',
            'review_status' => 'required',
            'student_id' => 'required|int|min:1',
            'reviewer_id' => '',
        ];
    }
}
