<?php

namespace App\Modules\Doctor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:100',
            'specialty_id' => 'sometimes|required|exists:specialties,id',
            'default_percentage' => 'sometimes|required|numeric|min:0|max:100',
        ];
    }
}
