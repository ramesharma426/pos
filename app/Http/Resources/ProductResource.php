<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            //'image_url' => url($this->image_url),
            'name' => $this->name,
            'unit' => $this->unit->name,
            'unit_id' => $this->unit_id,
            'category' => $this->category->name,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
            'created_at' => $this->created_at,
            'update-at' => $this->updated_at,

        ];
    }
}
