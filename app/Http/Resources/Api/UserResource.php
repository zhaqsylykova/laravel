<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->resource) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                // Другие свойства пользователя
            ];
        } else {
            // Если пользователь не существует, вернуть пустой массив или другой подходящий ответ
            return [];
        }
    }
}
