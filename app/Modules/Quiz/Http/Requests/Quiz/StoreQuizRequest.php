<?php

namespace App\Modules\Quiz\Http\Requests\Quiz;

use App\Modules\Quiz\Enums\QuizType;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuizRequest extends FormRequest
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
            'number_of_questions' => 'required|int|min:1',
            'number_of_tasks' => 'required|int|min:0',
            'number_of_quizzes' => 'required|int|min:0',
            'themes' => 'required',
            'theme_names' => 'required',
            'is_available' => 'required',
            'can_regenerate' => 'required',
            'is_midterm' => 'boolean',
            'assigned_to' => 'required',
            'type' => 'required|string|in:' . QuizType::casesAsString()
        ];
    }
}
