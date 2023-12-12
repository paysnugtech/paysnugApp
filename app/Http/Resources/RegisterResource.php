<?php

namespace App\Http\Resources;

use App\Http\Resources\ServicesResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResource extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        // $services = $this->service;

        return [
            'id' => $this->id,
            'profile' => $this->profile,
            // 'level' => $this->level,
            'role' => $this->role,
            // 'manager' => $this->manager,
            // 'notification' => $this->notification,
            // 'services' => $this->services,
            'verification' => $this->verification->load(['bill', 'bvn', 'card']),
            'device' => [
                'name' => $this->device->name,
                'type' => $this->device->type,
                'platform' => $this->device->platform,
                'signature' => $this->device->signature
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];

    }

}
