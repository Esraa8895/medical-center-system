<?php

namespace App\Livewire\TreatmentPlans;

use Livewire\Component;
use Livewire\WithPagination;
use App\Modules\TreatmentPlan\Models\TreatmentPlan;
use Illuminate\Support\Facades\DB;

class TreatmentPlanList extends Component
{
    use WithPagination;

    public string $search = '';
    public bool   $showModal       = false;
    public ?int   $editId          = null;
    public ?int   $selectedPatient = null;
    public ?int   $doctorId        = null;
    public ?int   $serviceId       = null;
    public string $totalSessions   = '';
    public string $expectedTotal   = '';
    public string $discount        = '0';
    public string $status          = 'active';
    public string $notes           = '';
    public string $patientSearch   = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function openModal(?int $id = null): void
    {
        $this->resetModal();
        $this->editId    = $id;
        $this->showModal = true;

        if ($id) {
            $plan = TreatmentPlan::findOrFail($id);
            $this->selectedPatient = $plan->patient_id;
            $this->doctorId        = $plan->doctor_id;
            $this->serviceId       = $plan->service_id;
            $this->totalSessions   = (string)($plan->total_sessions ?? '');
            $this->expectedTotal   = (string)$plan->expected_total;
            $this->discount        = (string)$plan->discount;
            $this->status          = $plan->status;
            $this->notes           = $plan->notes ?? '';
        }
    }

    public function closeModal(): void { $this->showModal = false; $this->resetModal(); }

    private function resetModal(): void
    {
        $this->editId = $this->selectedPatient = $this->doctorId = $this->serviceId = null;
        $this->totalSessions = $this->expectedTotal = $this->notes = $this->patientSearch = '';
        $this->discount = '0'; $this->status = 'active';
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $this->validate([
            'selectedPatient' => 'required|exists:patients,id',
            'doctorId'        => 'required|exists:doctors,id',
            'serviceId'       => 'required|exists:services,id',
            'totalSessions'   => 'nullable|integer|min:1',
            'expectedTotal'   => 'required|numeric|min:0',
            'discount'        => 'nullable|numeric|min:0',
        ], [
            'selectedPatient.required' => 'اختاري المريضة',
            'doctorId.required'        => 'اختاري الطبيب',
            'serviceId.required'       => 'اختاري الخدمة',
            'expectedTotal.required'   => 'أدخلي المبلغ الإجمالي',
        ]);

        $data = [
            'patient_id'     => $this->selectedPatient,
            'doctor_id'      => $this->doctorId,
            'service_id'     => $this->serviceId,
            'total_sessions' => $this->totalSessions ?: null,
            'expected_total' => $this->expectedTotal,
            'discount'       => $this->discount ?: 0,
            'status'         => $this->status,
            'notes'          => $this->notes ?: null,
        ];

        if ($this->editId) {
            TreatmentPlan::findOrFail($this->editId)->update($data);
            session()->flash('success', 'تم تعديل خطة العلاج ✅');
        } else {
            TreatmentPlan::create($data);
            session()->flash('success', 'تم إضافة خطة العلاج ✅');
        }

        $this->closeModal(); $this->resetPage();
    }

    public function delete(int $id): void
    {
        TreatmentPlan::findOrFail($id)->delete();
        session()->flash('success', 'تم حذف خطة العلاج');
    }

    public function render()
    {
        $patientResults = $this->patientSearch
            ? DB::table('patients')
                ->where('name',  'like', "%{$this->patientSearch}%")
                ->orWhere('phone', 'like', "%{$this->patientSearch}%")
                ->limit(8)->get()
            : collect();

        $selectedPatientName = $this->selectedPatient
            ? DB::table('patients')->where('id', $this->selectedPatient)->value('name')
            : null;

        $doctors  = DB::table('doctors')
            ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
            ->select('doctors.id', 'doctors.name', 'specialties.name as specialty_name')
            ->orderBy('doctors.name')->get();

        $services = DB::table('services')->orderBy('name')->get();

        $plans = TreatmentPlan::with(['patient', 'doctor', 'service'])
            ->when($this->search, fn($q) =>
                $q->whereHas('patient', fn($p) => $p->where('name', 'like', "%{$this->search}%")))
            ->latest()->paginate(10);

        return view('livewire.treatment-plans.treatment-plan-list',
            compact('plans', 'patientResults', 'selectedPatientName', 'doctors', 'services'))
            ->layout('components.layouts.app', ['title' => 'خطط العلاج']);
    }
}
