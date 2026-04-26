<?php

namespace App\Modules\Specialization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSpecialtyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:100',
        ];
    }
}
