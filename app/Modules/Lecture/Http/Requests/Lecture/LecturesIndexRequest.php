<?php

namespace App\Modules\Lecture\Http\Requests\Lecture;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LecturesIndexRequest extends FormRequest
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
            'fields' => 'array',
            'fields.*' => Rule::in([
                'id',
                'title',
                'excerpt',
                'content',
                'is_visible',
                //'user_id'
            ]),
            'only_id_and_title' => 'int'
        ];
    }
}
