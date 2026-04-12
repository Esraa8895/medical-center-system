<?php

namespace App\Modules\TreatmentPlan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTreatmentPlanRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'patient_id'        => 'sometimes|exists:patients,id',
            'doctor_id'         => 'sometimes|exists:doctors,id',
            'service_id'        => 'sometimes|exists:services,id',
            'total_sessions'    => 'nullable|integer|min:1',
            'completed_sessions'=> 'sometimes|integer|min:0',
            'status'            => 'sometimes|in:active,completed,cancelled',
            'notes'             => 'nullable|string',
            'discount'          => 'nullable|numeric|min:0',
        ];
    }
}