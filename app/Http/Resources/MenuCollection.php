<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MenuCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn($menu)=>[
                'name' => $menu->name,
                'identifier' => $menu->identifier,
                'description' => $menu->description,
                'active_status' => $menu->active_status,
                'items' => ItemCollection::make($menu->whenLoaded('items')),
            ])->all(),
        ];
    }
}
