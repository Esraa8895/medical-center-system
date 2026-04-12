<?php

namespace App\Modules\TreatmentPlan\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentPlanResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                 => $this->id,
            'patient'            => [
                'id'   => $this->patient?->id,
                'name' => $this->patient?->name,
            ],
            'doctor'             => [
                'id'   => $this->doctor?->id,
                'name' => $this->doctor?->name,
            ],
            'service'            => [
                'id'    => $this->service?->id,
                'name'  => $this->service?->name,
                'price' => $this->service?->price,
            ],
            'total_sessions'     => $this->total_sessions,
            'completed_sessions' => $this->completed_sessions,
            'expected_total'     => $this->expected_total,
            'discount'           => $this->discount,
            'status'             => $this->status,
            'notes'              => $this->notes,
            'created_at'         => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}