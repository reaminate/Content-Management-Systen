<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
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
            'title'=>[ 'string','sometimes'],
            'category_id'=>[ 'integer', 'exists:categories,id','sometimes'],
            'excerpt'=>[ 'string','sometimes'],
            'content'=>[ 'string','sometimes'],
            'image_id'=>['integer','exists:categories,id','sometimes'],
            'author_id'=>[ 'integer','exists:author,id','sometimes'],
            'published_at' => ['date', 'sometimes','sometimes'],
            'publication_status' => ['string', 'in:draft,published,archived'],
            'tags'=>['array','sometimes'],
            'tags.*'=>['integer', 'exists:tags,id','sometimes'],
        ];
    }
}
