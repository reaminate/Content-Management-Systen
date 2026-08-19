<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
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
            'identifier' => $this->identifier,
            'description' => $this->description,
            'active_status' => $this->active_status,
            'items' => ItemCollection::make($this->whenLoaded('items')),
        ];
    }
}
