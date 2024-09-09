<?php

namespace App\Modules\Quiz\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class AcceptQuizTakeForReviewRequest extends FormRequest
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
            // quiz take
            'quiz_take.id' => 'uuid|required',
            'quiz_take.quiz_id' => 'uuid|required',
            'quiz_take.started_at' => 'integer',
            'quiz_take.page_switches_count' => 'integer',
            'quiz_take.geolocation' => 'json',

            // selected
            'selected' => 'required|array',
            'selected.*.quiztake_id' => 'required|uuid|same:quiz_take.id',
            'selected.*.quiz_id' => 'required|uuid',
            'selected.*.question_id' => 'required|uuid',
            'selected.*.option_id' => 'nullable|uuid',
            'selected.*.type' => 'required|in:radio,checkbox,text,image',
            'selected.*.data' => 'array'
        ];
    }
}
