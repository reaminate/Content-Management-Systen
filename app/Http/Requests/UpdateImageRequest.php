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
            'image' => ['sometimes', 'file', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'stored_filename' => ['sometimes', 'string', 'unique:images,stored_filename'],
            'caption' => ['sometimes', 'string', 'max:255'],
            'for_author' => ['sometimes', 'boolean'],
        ];
    }
}
