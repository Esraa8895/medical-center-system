<?php

namespace App\Modules\VisitService\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisitServiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'visit_id'          => 'required|exists:visits,id',
            'service_id'        => 'required|exists:services,id',
            'price'             => 'required|numeric|min:0',
            'discount'          => 'nullable|numeric|min:0',
            'doctor_percentage' => 'nullable|numeric|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'visit_id.required'   => 'الزيارة مطلوبة',
            'service_id.required' => 'الخدمة مطلوبة',
            'price.required'      => 'السعر مطلوب',
        ];
    }
}