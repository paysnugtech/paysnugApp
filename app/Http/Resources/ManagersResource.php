<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ManagersResource extends JsonResource
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
            'first_name' => $this->first_name,
            'other_name' => $this->other_name,
            'phone_no' => $this->phone_no,
            'email' => $this->email,
            'whatsapp_no' => $this->whatsapp_no,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];

    }


 
    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {

        switch (strtolower($request->method())) {
            case 'post':
                $message = 'Created Successful';
                break;
            case 'delete':
                $message = 'Deleted Successful';
                break;
            case 'patch':
                $message = 'Updated Successful';
                break;
            case 'put':
                $message = 'Updated Successful';
                break;
            default:
                $message = 'Successful';
                break;
        }

        return [
            'status' => 'success',
            'message' => $message,
        ];
    }
}
