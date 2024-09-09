<?php

namespace App\Modules\Lecture\Http\Requests\Lecture;

use Illuminate\Foundation\Http\FormRequest;

class CreateLectureRequest extends FormRequest
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
            'excerpt' => 'required',
            'content' => 'required',
            'is_visible' => 'required'
        ];
    }
}
