<?php

namespace App\Http\Resources;

use App\Enums\ServiceTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionsResource extends JsonResource
{
    


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {

        if($this->service_type === ServiceTypeEnum::Airtime)
        {
            $data = $this->airtime;
        }
        else if($this->service_type === ServiceTypeEnum::Betting)
        {
            
            $data = $this->betting;
        }
        else if($this->service_type === ServiceTypeEnum::Cable)
        {
            
            $data = $this->cable;
        }
        else if($this->service_type === ServiceTypeEnum::Data)
        {
            
            $data = $this->data;
        }
        else if($this->service_type === ServiceTypeEnum::Electricity)
        {
            
            $data = $this->electricity;
        }
        else if($this->service_type === ServiceTypeEnum::BankTransfer)
        {
            
            $data = $this->transfer;
        }
        else
        {
            $data = [];
        }

        return [
            'id' => $this->id,
            'number' => $this->number,
            'amount' => $this->amount,
            'commission' => $this->commission,
            'discount' => $this->discount,
            'profit' => $this->profit,
            'balance_before' => $this->balance_before,
            'balance_after' => $this->balance_after,
            'type' => $this->type->name,
            'service_type' => $this->service_type->name,
            'data' => $data,
            'status' => $this->status->name,
            'narration' => $this->narration,
            'remark' => $this->remark,
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
