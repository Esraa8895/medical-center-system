<?php

namespace App\Modules\Appointment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'sometimes|exists:patients,id',
            'doctor_id' => 'sometimes|exists:doctors,id',
            'service_id' => 'sometimes|exists:services,id',
            'appointment_date' => 'sometimes|date|after:now',
            'type' => 'sometimes|in:first_visit,follow_up',
            'status' => 'sometimes|in:scheduled,cancelled,completed',
            'notes' => 'sometimes|string|max:1000',
        ];
    }
}
