<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
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
            'title'=>['required', 'string'],
            'category_id'=>['required', 'integer', 'exists:categories,id'],
            'excerpt'=>['required', 'string'],
            'content'=>['required', 'string'],
            'image_id'=>['integer','exists:images,id', 'nullable'],
            'author_id'=>['required', 'integer','exists:authors,id'],
            'tags'=>['array'],
            'tags.*'=>['integer', 'exists:tags,id'],
        ];
    }
}
