<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
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
            'profile' => $this->profile,
            'level' => $this->level,
            'role' => $this->role,
            'manager' => $this->manager,
            'notification' => $this->notification,
            'service' => $this->service,
            'verification' => $this->verification,
            'wallets' => $this->wallets->load('country'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];

    }

}
