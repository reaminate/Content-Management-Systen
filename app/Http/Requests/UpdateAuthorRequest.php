<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAuthorRequest extends FormRequest
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
            'name' => ['string', 'sometimes'],
            'email' => [ 'email', 'unique:authors,email', 'sometimes'],
            'short_biography' => ['string', 'sometimes'],
            'profile_pic'=>[ 'integer', 'exists:images,id', 'sometimes'],
        ];
    }
}
