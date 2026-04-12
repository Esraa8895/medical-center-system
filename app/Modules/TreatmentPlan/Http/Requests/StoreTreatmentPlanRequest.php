<?php

namespace App\Modules\TreatmentPlan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTreatmentPlanRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'patient_id'    => 'required|exists:patients,id',
            'doctor_id'     => 'required|exists:doctors,id',
            'service_id'    => 'required|exists:services,id',
            'total_sessions'=> 'nullable|integer|min:1',
            'status'        => 'sometimes|in:active,completed,cancelled',
            'notes'         => 'nullable|string',
            'discount'      => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'المريضة مطلوبة',
            'patient_id.exists'   => 'المريضة غير موجودة',
            'doctor_id.required'  => 'الطبيب مطلوب',
            'doctor_id.exists'    => 'الطبيب غير موجود',
            'service_id.required' => 'الخدمة مطلوبة',
            'service_id.exists'   => 'الخدمة غير موجودة',
        ];
    }
}