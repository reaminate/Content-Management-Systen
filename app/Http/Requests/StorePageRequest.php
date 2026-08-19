<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePageRequest extends FormRequest
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
            'title'=>['string', 'required', 'unique:pages,title'],
            'content'=>['string', 'required'],
            'content_image'=>['nullable', 'exists:images,id'],
            'description'=>['string', 'required'],
            'SEO_title'=>['string', 'required', 'unique:pages,SEO_title'],
            'SEO_description'=>['string','unique'],
        ];
    }
}
