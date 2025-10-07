<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'rate' => $this->rate,
            'delivered_at' => isset($this->delivered_at) ? $this->delivered_at->format("Y-m-d h:i:s a") : '',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
        //return parent::toArray($request);
    }
}
