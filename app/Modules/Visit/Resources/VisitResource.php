<?php

namespace App\Modules\Visit\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'patient'          => [
                'id'   => $this->patient?->id,
                'name' => $this->patient?->name,
            ],
            'doctor'           => [
                'id'   => $this->doctor?->id,
                'name' => $this->doctor?->name,
            ],
            'treatment_plan_id'=> $this->treatment_plan_id,
            'appointment_id'   => $this->appointment_id,
            'total_amount'     => $this->total_amount,
            'paid_cost'        => $this->paid_cost,
            'visit_services'   => $this->visitServices->map(fn($vs) => [
                'id'                => $vs->id,
                'service'           => $vs->service?->name,
                'price'             => $vs->price,
                'discount'          => $vs->discount,
                'doctor_percentage' => $vs->doctor_percentage,
                'clinic_percentage' => $vs->clinic_percentage,
                'doctor_share'      => $vs->doctor_share,
                'clinic_share'      => $vs->clinic_share,
            ]),
            'created_at'       => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}