<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */

    public function toArray($request)
    {
        return $this->resource ? [
            'product' => $this->product,
            'order_number' => $this->order_number,
            'quantity' => $this->quantity,
            'order_total' => $this->order_total,
            'status' => $this->status,
            'photo' => $this->photo,
            'user' => $this->user ? $this->user->name : null,

        ] : null;
    }
}
