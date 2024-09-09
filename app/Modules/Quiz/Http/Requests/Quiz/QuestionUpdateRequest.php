<?php

namespace App\Modules\Quiz\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class QuestionUpdateRequest extends FormRequest
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
            'uuid' => 'uuid',
            'title' => 'min:3',
            'content' => '',
            'type' => 'in:question,task,quiz',
            'theme' => 'int',
            'solution' => '',
            'is_visible' => 'boolean',
        ];
    }
}
