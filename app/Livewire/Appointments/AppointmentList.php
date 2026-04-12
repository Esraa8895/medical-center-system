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

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingStatus(): void { $this->resetPage(); }

    public function render()
    {
        $appointments = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->select('appointments.*', 'patients.name as patient_name', 'doctors.name as doctor_name', 'services.name as service_name')
            ->when($this->search, fn($q) => $q->where('patients.name', 'like', "%{$this->search}%"))
            ->when($this->status, fn($q) => $q->where('appointments.status', $this->status))
            ->orderByDesc('appointments.appointment_date')
            ->paginate(10);

        return view('livewire.appointments.appointment-list', compact('appointments'))
            ->layout('components.layouts.app', ['title' => 'المواعيد']);
    }
}