<?php

namespace App\Modules\Doctor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'specialty_id' => 'required|exists:specialties,id',
            'default_percentage' => 'required|numeric|min:0|max:100',
        ];
    }
}
