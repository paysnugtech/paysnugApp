<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CableBeneficiaryResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'customer_name' => $this->customer_name,
            'provider_name' => $this->provider_name
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
