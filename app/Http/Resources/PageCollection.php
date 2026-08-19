<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn($page)=>[
                'title' => $page->title,
                'content' => $page->content,
                'content_image'=> $page->content_image??null,
                'description'=> $page->description,
                'publication_status' => $page->publication_status,
                'SEO_title' => $page->SEO_title,
                'image'=> ImageResource::make($page->whenLoaded('image')),
                'item'=> ItemResource::make($page->whenLoaded('item')),

            ])->all(),
        ];
    }
}
