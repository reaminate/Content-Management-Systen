<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AuthorCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'date'=>$this->collection->map(fn($author)=>[
                'id' => $author->id,
                'name'=>(string)$author->name,
                'email' =>(string)$author->email,
                'biography' => $author->short_biography,
                'profile_pic' => ImageResource::make($author->whenLoaded('image')),
                'blogs_written' => BlogCollection::make($author->whenLoaded('blogs')),
            ])->all()
        ];
    }
}
