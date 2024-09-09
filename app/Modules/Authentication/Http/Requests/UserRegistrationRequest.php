<?php

namespace App\Modules\Authentication\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            /*'lastname' => 'required|min:3',
            'firstname' => 'required|min:3',
            'middlename' => '',*/
            'full_name' => 'required|min:6',
            'city_id' => 'exists:cities,id',
            'year_of_birth' => 'digits:4',
            'street' => '',
            'house' => '',
            'flat' => '',
            'phone' => ['required', 'regex:/^7[0-9]{10}$/', 'unique:users'],
            'password' => 'required|confirmed|min:6',
            'code' => ['required', 'digits:6']
            //'password_confirmation' => '',
        ];
    }
}
