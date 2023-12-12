<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BvnsResource extends JsonResource
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
            'is_verified' => $this->is_verified,
            'remark' => $this->remark,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
