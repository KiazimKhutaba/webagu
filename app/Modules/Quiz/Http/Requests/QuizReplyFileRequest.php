<?php

namespace App\Modules\Quiz\Http\Requests;

use App\Modules\Quiz\Enums\QuizFileableType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class QuizReplyFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:jpg,jpeg,png', //,doc,docx,csv,xlsx,xls,txt,pdf,pptx',
            'fileable_id' => 'required|int|exists:passed_quizzes,id',
            'fileable_type' => 'in:' . QuizFileableType::casesAsString()
        ];
    }
}
