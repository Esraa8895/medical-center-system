<?php

namespace App\Livewire\Visits;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Modules\Visit\Models\Visit;
use App\Modules\VisitService\Services\VisitServiceService;

class VisitList extends Component
{
    use WithPagination;

    // ── Filters ───────────────────────────────────────────
    public string $search = '';

    // ── Modal إضافة زيارة ────────────────────────────────
    public bool   $showVisitModal   = false;
    public string $patientSearch    = '';
    public ?int   $selectedPatient  = null;
    public ?int   $modalDoctorId    = null;

    // ── Modal إضافة خدمة لزيارة ──────────────────────────
    public bool   $showServiceModal = false;
    public ?int   $serviceVisitId   = null;   // الزيارة اللي بنضيف عليها الخدمة
    public ?int   $modalServiceId   = null;
    public float  $modalPrice       = 0;
    public float  $modalDiscount    = 0;

    // ── Modal عرض خدمات الزيارة ──────────────────────────
    public bool   $showServicesView = false;
    public ?int   $viewVisitId      = null;

    public function updatingSearch(): void { $this->resetPage(); }

    // ══════════════════════════════════════════════════════
    // VISIT MODAL
    // ══════════════════════════════════════════════════════
    public function openVisitModal(): void
    {
        $this->resetVisitModal();
        $this->showVisitModal = true;
    }

    public function closeVisitModal(): void
    {
        $this->showVisitModal = false;
        $this->resetVisitModal();
    }

    private function resetVisitModal(): void
    {
        $this->patientSearch   = '';
        $this->selectedPatient = null;
        $this->modalDoctorId   = null;
        $this->resetErrorBag();
    }

    public function saveVisit(): void
    {
        $this->validate([
            'selectedPatient' => 'required|exists:patients,id',
            'modalDoctorId'   => 'required|exists:doctors,id',
        ], [
            'selectedPatient.required' => 'اختاري المريضة',
            'modalDoctorId.required'   => 'اختاري الطبيب',
        ]);

        // created_at يأخذ تاريخ ووقت السيرفر تلقائياً
        Visit::create([
            'patient_id'   => $this->selectedPatient,
            'doctor_id'    => $this->modalDoctorId,
            'total_amount' => 0,
            'paid_cost'    => 0,
        ]);

        session()->flash('success', 'تم إنشاء الزيارة بنجاح ✅');
        $this->closeVisitModal();
        $this->resetPage();
    }

    public function deleteVisit(int $id): void
    {
        // soft delete — يُخفى من الواجهة ويبقى بالـ DB مع deleted_at
        Visit::findOrFail($id)->delete();
        session()->flash('success', 'تم أرشفة الزيارة');
    }

    // ══════════════════════════════════════════════════════
    // SERVICE MODAL — إضافة خدمة لزيارة
    // ══════════════════════════════════════════════════════
    public function openServiceModal(int $visitId): void
    {
        $this->serviceVisitId   = $visitId;
        $this->modalServiceId   = null;
        $this->modalPrice       = 0;
        $this->modalDiscount    = 0;
        $this->showServiceModal = true;
        $this->resetErrorBag();
    }

    public function closeServiceModal(): void
    {
        $this->showServiceModal = false;
        $this->serviceVisitId   = null;
        $this->modalServiceId   = null;
        $this->modalPrice       = 0;
        $this->modalDiscount    = 0;
    }

    // عند اختيار الخدمة — جلب السعر الافتراضي تلقائياً
    public function updatedModalServiceId(?int $value): void
    {
        if ($value) {
            $price = DB::table('services')->where('id', $value)->value('price');
            $this->modalPrice = $price ?? 0;
        }
    }

    public function saveService(): void
    {
        $this->validate([
            'modalServiceId' => 'required|exists:services,id',
            'modalPrice'     => 'required|numeric|min:0',
            'modalDiscount'  => 'nullable|numeric|min:0',
        ], [
            'modalServiceId.required' => 'اختاري الخدمة',
            'modalPrice.required'     => 'أدخلي السعر',
        ]);

        app(VisitServiceService::class)->create([
            'visit_id'   => $this->serviceVisitId,
            'service_id' => $this->modalServiceId,
            'price'      => $this->modalPrice,
            'discount'   => $this->modalDiscount ?? 0,
            'cost'       => 0,
        ]);

        session()->flash('success', 'تم إضافة الخدمة ✅');
        $this->closeServiceModal();
    }

    // ══════════════════════════════════════════════════════
    // VIEW SERVICES — عرض خدمات الزيارة
    // ══════════════════════════════════════════════════════
    public function viewServices(int $visitId): void
    {
        $this->viewVisitId      = $visitId;
        $this->showServicesView = true;
    }

    public function deleteService(int $serviceId): void
    {
        app(VisitServiceService::class)->delete($serviceId);
    }

    // ══════════════════════════════════════════════════════
    // RENDER
    // ══════════════════════════════════════════════════════
    public function render()
    {
        // قائمة الأطباء للـ modal
        $doctors = DB::table('doctors')
            ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
            ->select('doctors.id', 'doctors.name', 'specialties.name as specialty_name')
            ->orderBy('doctors.name')
            ->get();

        // قائمة الخدمات للـ modal
        $services = DB::table('services')->orderBy('name')->get();

        // بحث مريضة
        $patientResults = $this->patientSearch
            ? DB::table('patients')
                ->where('name',  'like', "%{$this->patientSearch}%")
                ->orWhere('phone', 'like', "%{$this->patientSearch}%")
                ->limit(8)->get()
            : collect();

        $selectedPatientName = $this->selectedPatient
            ? DB::table('patients')->where('id', $this->selectedPatient)->value('name')
            : null;

        // خدمات الزيارة المفتوحة
        $visitServices = $this->viewVisitId
            ? DB::table('visit_services')
                ->join('services', 'visit_services.service_id', '=', 'services.id')
                ->where('visit_services.visit_id', $this->viewVisitId)
                ->select(
                    'visit_services.id',
                    'services.name as service_name',
                    'visit_services.price',
                    'visit_services.discount',
                    DB::raw('visit_services.price - visit_services.discount as net')
                )
                ->get()
            : collect();

        // اسم مريضة الزيارة المفتوحة
        $viewVisitPatientName = $this->viewVisitId
            ? DB::table('visits')
                ->join('patients', 'visits.patient_id', '=', 'patients.id')
                ->where('visits.id', $this->viewVisitId)
                ->value('patients.name')
            : null;

        // الزيارات
        $visits = DB::table('visits')
            ->join('patients', 'visits.patient_id', '=', 'patients.id')
            ->join('doctors',  'visits.doctor_id',  '=', 'doctors.id')
            ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
            ->leftJoin(DB::raw(
                '(SELECT visit_id, COUNT(*) as services_count
                  FROM visit_services GROUP BY visit_id) as vs'
            ), 'vs.visit_id', '=', 'visits.id')
            ->select(
                'visits.id',
                'visits.total_amount',
                'visits.paid_cost',
                'visits.created_at',
                'patients.name  as patient_name',
                'doctors.name   as doctor_name',
                'specialties.name as specialty_name',
                DB::raw('COALESCE(vs.services_count, 0) as services_count'),
                DB::raw('visits.total_amount - visits.paid_cost as remaining')
            )
            ->when($this->search, fn($q) =>
                $q->where('patients.name', 'like', "%{$this->search}%"))
            ->orderByDesc('visits.created_at')
            ->paginate(10);

        return view('livewire.visits.visit-list', compact(
            'visits', 'doctors', 'services',
            'patientResults', 'selectedPatientName',
            'visitServices', 'viewVisitPatientName'
        ))->layout('components.layouts.app', ['title' => 'الزيارات']);
    }
}
