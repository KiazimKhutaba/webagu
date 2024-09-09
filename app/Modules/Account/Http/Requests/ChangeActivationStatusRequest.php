<?php

namespace App\Modules\Account\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeActivationStatusRequest extends FormRequest
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
            'status' => 'required|in:activated,not_activated',
            'user_id' => 'required|int'
        ];
    }
}
