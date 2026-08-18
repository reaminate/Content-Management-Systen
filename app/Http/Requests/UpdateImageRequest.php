<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateImageRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'stored_filename'=>[ 'unique:images,stored_filename', 'string'],
            'file_path'=>['file', 'mimes:png,jpg,jpeg'],
            'caption' => 'string',
            'upload_date'=>['date']
        ];
    }
}
