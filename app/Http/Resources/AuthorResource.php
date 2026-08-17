<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name'=>(string)$this->name,
            'email' =>(string)$this->email,
            'biography' => $this->short_biography,
            'profile_pic' => ImageResource::make($this->whenLoaded('image')),
            'blogs_written' => BlogCollection::make($this->whenLoaded('blogs')),
        ];
    }
}
