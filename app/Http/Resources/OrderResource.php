<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'customer_name' => $this->customer_name ?? '-',
            //'customer_address' => $this->customer_address ?? '-',
            //'customer_phone_number' => $this->customer_phone_number ?? '-',
            'table_number' => $this->table->number,
            'table_id' => $this->table_id,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at,
            'bill_number' => isset($this->payment->order_id) ? $this->payment->bill_number : "",
            'discount' => isset($this->payment->discount) ? (float) $this->payment->discount : ""
        ];
    }
}
