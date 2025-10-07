<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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
            'product' => $this->product->name,
            'quantity' => $this->quantity,
            'cost' => $this->cost,
            'unit' => $this->product->unit->name,
            'created_at' => $this->created_at->format('Y-m-d')
        ];
    }
}
