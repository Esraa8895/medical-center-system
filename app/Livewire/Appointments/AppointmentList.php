<?php

namespace App\Livewire\Appointments;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class AppointmentList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $specialty = '';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingStatus(): void { $this->resetPage(); }
    public function updatingSpecialty(): void { $this->resetPage(); }

    public function render()
    {
        $specialties = DB::table('specialties')->get();

        $appointments = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->join('doctors',  'appointments.doctor_id',  '=', 'doctors.id')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
            ->select(
                'appointments.*',
                'patients.name as patient_name',
                'doctors.name as doctor_name',
                'services.name as service_name',
                'specialties.name as specialty_name'
            )
            ->when($this->search, fn($q) => $q->where('patients.name', 'like', "%{$this->search}%"))
            ->when($this->status, fn($q) => $q->where('appointments.status', $this->status))
            ->when($this->specialty, fn($q) => $q->where('specialties.id', $this->specialty))
            ->orderByDesc('appointments.appointment_date')
            ->paginate(10);

        return view('livewire.appointments.appointment-list', compact('appointments', 'specialties'))
            ->layout('components.layouts.app', ['title' => 'المواعيد']);
    }
}