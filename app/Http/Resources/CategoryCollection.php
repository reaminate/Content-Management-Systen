<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn($category)=>[
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'is_active' => $category->active_status,
                'blogs_under' => BlogCollection::make($category->whenLoaded('blog')),
            ])->all(),
        ];
    }
}
