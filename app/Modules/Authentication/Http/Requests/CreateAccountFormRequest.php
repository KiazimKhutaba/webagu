<?php

namespace App\Modules\Authentication\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAccountFormRequest extends FormRequest
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
            'firstname' => 'required|min:3',
            'lastname' => 'required|min:6',
            'department' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => ['required','regex:/^(9|7)[0-9]{6}$/', 'unique:users'],
            'password' => 'required|confirmed|min:6',
            'avatar' => 'required|mimes:jpg,jpeg,png',
        ];
    }

    public function messages():array
    {
        return [
            'email.required' => 'Необходимо заполнить поле Почта',
            'avatar.required' => 'Добавьте фотографию профиля'
        ];
    }
}
