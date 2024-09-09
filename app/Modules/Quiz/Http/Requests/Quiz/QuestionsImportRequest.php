<?php

namespace App\Modules\Quiz\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class QuestionsImportRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'list' => 'required|array',
            'list.*.title' => 'required',
            'list.*.theme' => 'required',
            'list.*.type' => 'required',
            'list.*.content' => '',
            'list.*.solution' => '',
            'list.*.uuid' => 'uuid'
        ];
    }
}
