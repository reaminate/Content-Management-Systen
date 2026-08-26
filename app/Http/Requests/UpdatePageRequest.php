<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePageRequest extends FormRequest
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
            'title'=>['string', 'unique:pages,title', 'sometimes'],
            'content'=>['string', 'sometimes'],
            'content_image'=>['nullable', 'exists:images,id', 'sometimes'],
            'description'=>['string', 'sometimes'],
            'publication_status' => ['string', 'in:draft,published,archived', 'sometimes'],
            'publication_date' => ['date', 'sometimes'],
            'SEO_title'=>['string', 'unique:pages,SEO_title', 'sometimes'],
            'SEO_description'=>['string', 'sometimes'],
        ];
    }
}
