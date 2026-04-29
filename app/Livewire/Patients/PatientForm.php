<?php

namespace App\Livewire\Patients;

use Livewire\Component;
use App\Modules\Patient\Models\Patient;

class PatientForm extends Component
{
    public ?int $patientId = null;

    public string $name               = '';
    public string $phone              = '';
    public string $age                = '';
    public string $previous_diseases  = '';

    public function mount(?int $patientId = null): void
    {
        $this->patientId = $patientId;

        if ($patientId) {
            $patient = Patient::findOrFail($patientId);
            $this->name              = $patient->name;
            $this->phone             = $patient->phone ?? '';
            $this->age               = (string)($patient->age ?? '');
            $this->previous_diseases = $patient->previous_diseases ?? '';
        }
    }

    protected function rules(): array
    {
        return [
            'name'              => 'required|string|max:100',
            'phone'             => ['nullable', 'regex:/^(\+963|0)?9[0-9]{8}$/'],
            'age'               => 'nullable|integer|min:1|max:120',
            'previous_diseases' => 'nullable|string|max:1000',
        ];
    }

    protected array $messages = [
        'name.required' => 'اسم المريضة مطلوب',
        'name.max'      => 'الاسم يجب أن لا يتجاوز 100 حرف',
        'age.integer'   => 'العمر يجب أن يكون رقماً صحيحاً',
        'age.min'       => 'العمر يجب أن يكون أكبر من 0',
        'age.max'       => 'العمر يجب أن لا يتجاوز 120',
        'phone.regex'   => 'رقم الهاتف يجب أن يكون رقماً سورياً صحيحاً (مثال: 0912345678)',
    ];

    public function save(): void
    {
        $data = $this->validate();
        $data['age']   = $data['age']   ? (int)$data['age']   : null;
        $data['phone'] = $data['phone'] ?: null;

        if ($this->patientId) {
            Patient::findOrFail($this->patientId)->update($data);
            session()->flash('success', 'تم تعديل بيانات المريضة بنجاح');
        } else {
            Patient::create($data);
            session()->flash('success', 'تم إضافة المريضة بنجاح');
        }

        $this->dispatch('patient-saved');
    }

    public function render()
    {
        return view('livewire.patients.patient-form');
    }
}
