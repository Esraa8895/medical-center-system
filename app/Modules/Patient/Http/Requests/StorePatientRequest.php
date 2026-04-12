<?php

namespace App\Modules\Patient\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:100',
            'phone'             => 'nullable|string|max:20',
            'age'               => 'nullable|integer|min:0|max:150',
            'previous_diseases' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المريضة مطلوب',
            'name.max'      => 'الاسم لا يتجاوز 100 حرف',
            'age.integer'   => 'العمر يجب أن يكون رقماً',
            'age.min'       => 'العمر لا يمكن أن يكون سالباً',
        ];
    }
}