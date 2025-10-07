<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            'product_variant' => $this->product_variant->name,
            'quantity' => $this->quantity,
            'cost_price' => $this->cost_price,
            'sales_price' => $this->sales_price,
            'created_at' => $this->created_at
        ];
    }
}
