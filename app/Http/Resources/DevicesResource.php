<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DevicesResource extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'device_name' => $this->device_name,
            'device_id' => $this->device_id,
            'device_type' => $this->device_type,
            'platform' => $this->platform,
            'signature' => $this->signature,
            'ip' => $this->ip,
            'status' => $this->status,
            'user' => $this->user->load('profile'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

}
