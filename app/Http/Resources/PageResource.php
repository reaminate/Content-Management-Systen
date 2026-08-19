<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'content_image'=> $this->content_image??null,
            'description'=> $this->description,
            'publication_status' => $this->publication_status,
            'SEO_title' => $this->SEO_title,
            'image'=> ImageResource::make($this->whenLoaded('image')),
            'item'=> ItemResource::make($this->whenLoaded('item')),
        ];
    }
}
