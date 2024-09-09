<?php

namespace App\Modules\Account\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordChangeRequest extends FormRequest
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
            'phone' => ['required','regex:/^(9|7)[0-9]{6}$/', 'exists:users'],
            'password' => 'required|confirmed|min:6',
            // 'password_confirmation' => ''
        ];
    }
}
