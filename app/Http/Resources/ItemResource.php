<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'label' => $this->label,
            'url' => $this->url,
            'menu_id' => $this->menu_id,
            'menu' => MenuResource::make($this->whenLoaded('menu')),
            'page' => PageResource::make($this->whenLoaded('page')),
        ];
    }
}
