<?php

namespace App\Modules\Quiz\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GrantUserAccessToQuizRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|int|exists:users,id',
            'request_id' => 'required|int|exists:access_requests,id'
        ];
    }
}
