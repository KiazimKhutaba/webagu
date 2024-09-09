<?php

namespace App\Modules\OpenForms\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFormQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // quiz
            'quiz.id' => 'required|uuid',
            'quiz.title' =>  'required|string',
            'quiz.lecture_id' => 'required|integer',
            'quiz.description' => 'required|string',
            'quiz.duration' => 'required|integer',
            'quiz.is_available' =>  'required|boolean',
            'quiz.shuffle_questions' => 'required|boolean',

            // quiz.questions
            'questions.*.id' =>  '',
            'questions.*.quiz_id' =>  '',
            'questions.*.title' =>  '',
            'questions.*.image' =>  '',
            'questions.*.variant_type' =>  '',
            'questions.*.description' =>  '',
            'questions.*.has_description' =>  '',
            //'questions.*.selected' =>  '',
            'questions.*.points' =>  '',
            'questions.*.required' =>  '',

            // options
            'options' => 'nullable|array',
            'options.*.id' => '',
            'options.*.question_id' => '',
            'options.*.quiz_id' => '',
            'options.*.text' => '',
            'options.*.is_checked' => '',
            'options.*.control_type' => ''
        ];
    }
}
