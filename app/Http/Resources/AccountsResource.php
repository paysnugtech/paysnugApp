<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountsResource extends JsonResource
{
    


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'account_name' => $this->account_name,
            'account_number' => $this->account_number
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
