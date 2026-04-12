<?php

namespace App\Modules\VisitService\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVisitServiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'price'             => 'sometimes|numeric|min:0',
            'discount'          => 'nullable|numeric|min:0',
            'doctor_percentage' => 'nullable|numeric|min:0|max:100',
        ];
    }
}