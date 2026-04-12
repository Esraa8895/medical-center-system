<?php

namespace App\Modules\Patient\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'phone'             => $this->phone,
            'age'               => $this->age,
            'previous_diseases' => $this->previous_diseases,
            'created_at'        => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}