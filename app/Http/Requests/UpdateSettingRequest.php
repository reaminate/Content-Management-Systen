<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
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
            'description' => ['string', 'sometimes'],
            'email' => ['string', 'sometimes'],
            'phone' => ['string', 'sometimes'],
            'address' => ['string', 'sometimes'],
            'facebook' => ['url', 'sometimes'],
            'linkedin' => ['url', 'sometimes'],
            'instagram' => ['url', 'sometimes'],
            'SEO_title' => ['string', 'sometimes'],
            'SEO_description' => ['string', 'sometimes'],
        ]; 
    }
}
