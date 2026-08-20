<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'facebook_url' => $this->facebook,
            'linkedin_url' => $this->linkedin,
            'instagram' => $this->instagram,
            'SEO' => [
                'title' => $this->SEO_title,
                'description' => $this->SEO_description,
            ], 
        ];
    }
}
