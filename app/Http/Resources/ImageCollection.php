<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ImageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn($image)=>[
                'id' => $image->id,
                'og_filename' => $image->original_filename,
                'stored_filename'=> $image->stored_filename,
                'file_type' => $image->file_type,
                'for_author'=> $image->for_author,
                'caption' => $image->caption,
                'profile_pic' => ImageResource::make($image->whenLoaded('author')),
                'blogs_image' => ImageCollection::make($image->whenLoaded('blogs')),
                'page_image' => ImageCollection::make($image->whenLoaded('pages')),
            ])
        ];
    }
}
