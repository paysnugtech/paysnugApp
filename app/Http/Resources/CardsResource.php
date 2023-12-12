<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardsResource extends JsonResource
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
            'number' => $this->number,
            'front_url' => $this->front_url,
            'back_url' => $this->back_url,
            'is_verified' => $this->is_verified,
            'remark' => $this->remark,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
