<?php

namespace App\Modules\Task\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserTaskRequest extends FormRequest
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
            'title' => 'required',
            'description' => 'required',
            'is_visible' => 'required',
            'lecture_id' => 'required',
        ];
    }
}
