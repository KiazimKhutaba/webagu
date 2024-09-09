<?php

namespace App\Modules\Task\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserTasksColumnsRequest extends FormRequest
{
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
            'fields' => 'array',
            'fields.*' => Rule::in([
                'id',
                'title',
                'description',
                'is_visible',
                'lecture_id',
                'is_seminar',
                //'user_id'
            ]),
        ];
    }
}
