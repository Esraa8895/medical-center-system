<?php

namespace App\Livewire\Patients;

use Livewire\Component;
use App\Modules\Patient\Models\Patient;
use Illuminate\Support\Facades\DB;

class PatientProfile extends Component
{
    public int $patientId;
    public $patient;
    public $treatmentPlans;
    public $visits;
    public $payments;
    public $totalPaid;
    public $totalAmount;

    public function mount(int $id): void
    {
        $this->patientId      = $id;
        $this->patient        = Patient::findOrFail($id);

        $this->treatmentPlans = DB::table('treatment_plans')
            ->join('doctors',  'treatment_plans.doctor_id',  '=', 'doctors.id')
            ->join('services', 'treatment_plans.service_id', '=', 'services.id')
            ->where('treatment_plans.patient_id', $id)
            ->select('treatment_plans.*', 'doctors.name as doctor_name', 'services.name as service_name')
            ->latest('treatment_plans.created_at')
            ->get();

        $this->visits = DB::table('visits')
            ->join('doctors', 'visits.doctor_id', '=', 'doctors.id')
            ->where('visits.patient_id', $id)
            ->select('visits.*', 'doctors.name as doctor_name')
            ->latest('visits.created_at')
            ->get();

        $this->payments = DB::table('payments')
            ->where('patient_id', $id)
            ->latest('created_at')
            ->get();

        $this->totalPaid   = $this->payments->sum('amount');
        $this->totalAmount = $this->visits->sum('total_amount');
    }

    public function render()
    {
        return view('livewire.patients.patient-profile')
            ->layout('components.layouts.app', ['title' => 'ملف المريضة']);
    }
}