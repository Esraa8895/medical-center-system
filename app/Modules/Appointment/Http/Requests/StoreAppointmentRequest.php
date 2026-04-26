<?php

namespace App\Modules\Appointment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:now',
            'type' => 'required|in:first_visit,follow_up',
            'status' => 'sometimes|in:scheduled,cancelled,completed',
            'notes' => 'sometimes|string|max:1000',
        ];
    }
}
