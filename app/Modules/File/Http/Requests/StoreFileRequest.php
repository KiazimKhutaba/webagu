<?php

namespace App\Modules\File\Http\Requests;

use App\Common\Enums\FileableType;
use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
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
            'file' => 'required|file|mimes:jpg,jpeg,png,doc,docx,csv,xlsx,xls,txt,pdf,pptx',
            'fileable_type' => 'required|in:' . FileableType::casesAsString(),
            'dest' => ''
        ];
    }
}
