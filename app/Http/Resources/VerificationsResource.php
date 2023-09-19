<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VerificationsResource extends JsonResource
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
            'bvn' => $this->bvn,
            'card' => $this->card,
            'bill' => $this->bill,
            'email_verified' => $this->email_verified,
            'phone_verified' => $this->phone_verified,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
