<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
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
            'og_filename' => $this->original_filename,
            'stored_filename'=> $this->stored_filename,
            'file_type' => $this->file_type,
            'for_author'=> $this->for_author,
            'caption' => $this->caption,
            'profile_pic' => AuthorResource::make($this->whenLoaded('author')),
            'blogs_image' => BlogCollection::make($this->whenLoaded('blogs')),
            'page_image' => PageCollection::make($this->whenLoaded('pages')),
        ];
    }
}
