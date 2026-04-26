<?php

namespace App\Modules\Service\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        $serviceId = $this->route('service');

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:150',
                Rule::unique('services', 'name')->ignore($serviceId),
            ],

            'specialty_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:specialties,id',
            ],

            'price' => [
                'sometimes',
                'required',
                'numeric',
                'min:0.01',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الخدمة مطلوب.',
            'name.unique'   => 'اسم الخدمة موجود مسبقاً.',

            'specialty_id.exists' => 'التخصص غير موجود.',

            'price.numeric' => 'السعر يجب أن يكون رقم.',
            'price.min'     => 'السعر يجب أن يكون أكبر من صفر.',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => trim($this->name),
            ]);
        }

        if ($this->has('price')) {
            $this->merge([
                'price' => str_replace(',', '', $this->price),
            ]);
        }
    }
}
