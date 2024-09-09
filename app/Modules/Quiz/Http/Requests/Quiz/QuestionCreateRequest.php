<?php

namespace App\Modules\Quiz\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class QuestionCreateRequest extends FormRequest
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
            'uuid' => 'required|uuid',
            'title' => 'required|min:3',
            'content' => '',
            'type' => 'required|in:question,task,quiz',
            'theme' => 'required|int',
            'solution' => ''
        ];
    }
}
