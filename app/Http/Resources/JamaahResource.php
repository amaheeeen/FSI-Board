<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JamaahResource extends JsonResource
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
            'name' => $this->name,
            'passport_number' => $this->passport_number,
            'passport_expiry' => $this->passport_expiry?->format('Y-m-d'),
            'gender' => $this->gender,
            'phone' => $this->phone,
            'active_packet' => $this->transactionDetails->first()?->transaction->packet->name ?? 'None',
        ];
    }
}
