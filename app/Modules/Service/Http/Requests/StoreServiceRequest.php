<?php

namespace App\Modules\Service\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('services', 'name'),
            ],

            'specialty_id' => [
                'required',
                'integer',
                'exists:specialties,id',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0.01', // ❗ لازم يكون أكبر من صفر
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الخدمة مطلوب.',
            'name.unique'   => 'اسم الخدمة موجود مسبقاً.',
            'name.max'      => 'اسم الخدمة طويل جداً.',

            'specialty_id.required' => 'التخصص مطلوب.',
            'specialty_id.exists'   => 'التخصص غير موجود.',

            'price.required' => 'السعر مطلوب.',
            'price.numeric'  => 'السعر يجب أن يكون رقم.',
            'price.min'      => 'السعر يجب أن يكون أكبر من صفر.',
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
