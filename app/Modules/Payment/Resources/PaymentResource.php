<?php

namespace App\Modules\Payment\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'visit_id'      => $this->visit_id,
            'patient_id'    => $this->patient_id,
            'amount'        => $this->amount,
            'exchange_rate' => $this->exchange_rate,
            'notes'         => $this->notes,
            'created_at'    => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
