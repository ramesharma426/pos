<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "order_id" => $this->order_id,
            "bill_number" => $this->bill_number,
            "discount" => $this->discount,
            "bill_date" => $this->created_at->format('Y-m-d'),
            "table_number" => $this->order->table->number,
            "customer_name" => $this->order->customer_name ?? "-"
        ];
    }
}
