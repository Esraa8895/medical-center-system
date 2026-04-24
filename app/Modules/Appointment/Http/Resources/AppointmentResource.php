<?php

namespace App\Modules\Appointment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'patient' => [
                'id' => $this->patient->id,
                'name' => $this->patient->name,
            ],

            'doctor' => [
                'id' => $this->doctor->id,
                'name' => $this->doctor->name,
            ],

            'service' => [
                'id' => $this->service->id,
                'name' => $this->service->name,
            ],

            'appointment_date' => $this->appointment_date,
            'type' => $this->type,
            'status' => $this->status,
            'notes' => $this->notes,

            'created_at' => $this->created_at,
        ];
    }
}
