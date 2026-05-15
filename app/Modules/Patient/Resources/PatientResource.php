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
            'address'           => $this->address,
            'age'               => $this->age,
            'notes'             => $this->notes,
            'created_at'        => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}
