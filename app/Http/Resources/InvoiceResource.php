<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'invoice_number' => $this->code,
            'amount_total' => $this->total_amount,
            'amount_paid' => $this->paid_amount,
            'balance' => $this->total_amount - $this->paid_amount,
            'status' => $this->payment_status, // pending, paid, partial
            'packet_name' => $this->packet->name,
            'due_date' => $this->packet->start_date->subDays(30)->format('Y-m-d'), // Mock due date
            'virtual_account' => '8800' . str_pad($this->id, 8, '0', STR_PAD_LEFT), // Mock VA
        ];
    }
}
