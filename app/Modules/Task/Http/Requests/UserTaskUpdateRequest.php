<?php

namespace App\Modules\Task\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserTaskUpdateRequest extends FormRequest
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
            'title' => '',
            'description' => '',
            'is_visible' => '',
            'lecture_id' => 'int',
            'expired_at' => 'int',
            'is_seminar' => 'bool'
        ];
    }
}
