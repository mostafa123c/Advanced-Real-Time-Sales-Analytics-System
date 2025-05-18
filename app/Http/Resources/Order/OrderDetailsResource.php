<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
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
            'total_order_price' => $this->total_price,
            'product' => [
                'id' => $this->product_id,
                'name' => $this->name,
                'price' => $this->price,
                'quantity' => $this->quantity,
            ],
            'created_at' => $this->created_at,
        ];
    }
}
