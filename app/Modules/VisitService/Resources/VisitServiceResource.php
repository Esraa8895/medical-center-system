<?php

namespace App\Modules\VisitService\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitServiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'visit_id'          => $this->visit_id,
            'service'           => [
                'id'   => $this->service?->id,
                'name' => $this->service?->name,
            ],
            'price'             => $this->price,
            'discount'          => $this->discount,
            'doctor_percentage' => $this->doctor_percentage,
            'clinic_percentage' => $this->clinic_percentage,
            'doctor_share'      => $this->doctor_share,
            'clinic_share'      => $this->clinic_share,
            'created_at'        => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}