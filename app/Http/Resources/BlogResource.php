<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
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
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'tags' => TagCollection::make($this->whenLoaded('tags')),
            'author' => AuthorResource::make($this->whenLoaded('author')),
            'image' => ImageResource::make($this->whenLoaded('image')),
            'category' => CategoryResource::make($this->whenLoaded('category')),
        ];
    }
}
