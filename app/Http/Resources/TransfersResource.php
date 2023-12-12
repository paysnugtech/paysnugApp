<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransfersResource extends JsonResource
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
            'transaction_no' => $this->number,
            'amount' => $this->amount,
            'fee' => $this->commission,
            'discount' => $this->discount,
            'profit' => $this->profit,
            'type' => $this->type->name,
            'service_type' => $this->service_type->name,
            'balance_before' => $this->balance_before,
            'balance_after' => number_format($this->balance_after, 2, '.', ''),
            'reference_no' => $this->reference_no,
            'narration' => $this->narration,
            'transfer' => $this->transfer,
            'status' => $this->status->name,
            'remark' => $this->remark,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];

    }

}
