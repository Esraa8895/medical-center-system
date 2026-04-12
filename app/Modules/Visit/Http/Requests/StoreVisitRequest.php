<?php

namespace App\Modules\Visit\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisitRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'patient_id'        => 'required|exists:patients,id',
            'doctor_id'         => 'required|exists:doctors,id',
            'treatment_plan_id' => 'nullable|exists:treatment_plans,id',
            'appointment_id'    => 'nullable|exists:appointments,id',
            'paid_cost'         => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'المريضة مطلوبة',
            'doctor_id.required'  => 'الطبيب مطلوب',
        ];
    }
}