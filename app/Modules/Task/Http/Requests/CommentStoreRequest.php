<?php

namespace App\Modules\Task\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentStoreRequest extends FormRequest
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
            //'commentable_type' => 'required',
            'receiver_id' => 'required|int',
            'commentable_id' => 'required',
            'message' => 'required',
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png'
        ];
    }
}
