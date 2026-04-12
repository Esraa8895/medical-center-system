<?php

namespace App\Livewire\Patients;

use Livewire\Component;
use Livewire\WithPagination;
use App\Modules\Patient\Models\Patient;

class PatientList extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showForm = false;
    public ?int $editId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openForm(?int $id = null): void
    {
        $this->editId = $id;
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->editId = null;
    }

    public function deletePatient(int $id): void
    {
        Patient::findOrFail($id)->delete();
        session()->flash('success', 'تم حذف المريضة بنجاح');
    }

    public function render()
    {
        $patients = Patient::where('name', 'like', "%{$this->search}%")
            ->latest()
            ->paginate(10);

        return view('livewire.patients.patient-list', compact('patients'))
            ->layout('components.layouts.app', ['title' => 'المرضى']);
    }
}
