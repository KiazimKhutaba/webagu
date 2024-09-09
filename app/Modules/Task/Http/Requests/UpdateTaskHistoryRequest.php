<?php

namespace App\Modules\Task\Http\Requests;

use App\Modules\Task\Enums\TaskStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskHistoryRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'task_id' => 'required|int|exists:tasks,id',
            'status' => 'required|string|in:' . TaskStatus::casesAsString(),
            'student_id' => 'sometimes|int|exists:users,id',
            'files.*' => 'required|file|mimes:jpg,jpeg,png,doc,docx,csv,xlsx,xls,txt,pdf,pptx',
            'message' => 'nullable|string',
        ];
    }
}
