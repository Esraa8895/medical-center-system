<?php

namespace App\Livewire\Appointments;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Modules\Appointment\Models\Appointment;

class AppointmentList extends Component
{
    use WithPagination;

    // ── Filters ───────────────────────────────────────────
    public string $search    = '';
    public string $status    = '';
    public string $specialty = '';   // ← الاسم الصحيح (كان specialty في PHP وspecialtyId في blade — الآن متطابقان)

    // ── Modal إضافة موعد ──────────────────────────────────
    public bool   $showModal        = false;
    public string $patientSearch    = '';
    public ?int   $selectedPatient  = null;
    public ?int   $modalDoctorId    = null;
    public ?int   $modalServiceId   = null;
    public string $appointmentDate  = '';
    public string $appointmentType  = 'first_visit';
    public string $appointmentNotes = '';

    // ── Modal تعديل الحالة ────────────────────────────────
    public bool   $showStatusModal    = false;
    public ?int   $editStatusId       = null;
    public string $editStatus         = '';

    public function updatingSearch():    void { $this->resetPage(); }
    public function updatingStatus():    void { $this->resetPage(); }
    public function updatingSpecialty(): void { $this->resetPage(); }

    // ── فتح/إغلاق modal الإضافة ──────────────────────────
    public function openModal(): void
    {
        $this->resetModal();
        $this->appointmentDate = now()->format('Y-m-d\TH:i');
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetModal();
    }

    private function resetModal(): void
    {
        $this->patientSearch    = '';
        $this->selectedPatient  = null;
        $this->modalDoctorId    = null;
        $this->modalServiceId   = null;
        $this->appointmentDate  = '';
        $this->appointmentType  = 'first_visit';
        $this->appointmentNotes = '';
        $this->resetErrorBag();
    }

    // ── حفظ الموعد ───────────────────────────────────────
    public function saveAppointment(): void
    {
        $this->validate([
            'selectedPatient' => 'required|exists:patients,id',
            'modalDoctorId'   => 'required|exists:doctors,id',
            'modalServiceId'  => 'required|exists:services,id',
            'appointmentDate' => 'required|date',
        ], [
            'selectedPatient.required' => 'اختاري المريضة',
            'modalDoctorId.required'   => 'اختاري الطبيب',
            'modalServiceId.required'  => 'اختاري الخدمة',
            'appointmentDate.required' => 'حددي تاريخ الموعد',
        ]);

        DB::table('appointments')->insert([
            'patient_id'       => $this->selectedPatient,
            'doctor_id'        => $this->modalDoctorId,
            'service_id'       => $this->modalServiceId,
            'appointment_date' => $this->appointmentDate,
            'type'             => $this->appointmentType,
            'notes'            => $this->appointmentNotes ?: null,
            'status'           => 'scheduled',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        session()->flash('success', 'تم حجز الموعد بنجاح ✅');
        $this->closeModal();
        $this->resetPage();
    }

    // ── تعديل حالة الموعد ────────────────────────────────
    public function openStatusModal(int $id, string $currentStatus): void
    {
        $this->editStatusId = $id;
        $this->editStatus   = $currentStatus;
        $this->showStatusModal = true;
    }

    public function saveStatus(): void
    {
        $this->validate([
            'editStatus' => 'required|in:scheduled,completed,cancelled',
        ]);

        DB::table('appointments')
            ->where('id', $this->editStatusId)
            ->update(['status' => $this->editStatus, 'updated_at' => now()]);

        session()->flash('success', 'تم تحديث الحالة');
        $this->showStatusModal = false;
        $this->editStatusId    = null;
    }

    // ── حذف موعد ─────────────────────────────────────────
    public function deleteAppointment(int $id): void
    {
        // soft delete — يُخفى من الواجهة ويبقى بالـ DB مع deleted_at
        Appointment::findOrFail($id)->delete();
        session()->flash('success', 'تم أرشفة الموعد');
    }

    // ── Render ────────────────────────────────────────────
    public function render()
    {
        $specialties = DB::table('specialties')->orderBy('name')->get();

        // قائمة الأطباء للـ modal
        $doctors = DB::table('doctors')
            ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
            ->select('doctors.id', 'doctors.name', 'specialties.name as specialty_name')
            ->orderBy('doctors.name')
            ->get();

        // قائمة الخدمات للـ modal
        $services = DB::table('services')->orderBy('name')->get();

        // البحث عن مريضة في الـ modal
        $patientResults = $this->patientSearch
            ? DB::table('patients')
                ->where('name', 'like', "%{$this->patientSearch}%")
                ->orWhere('phone', 'like', "%{$this->patientSearch}%")
                ->limit(8)
                ->get()
            : collect();

        // المريضة المختارة
        $selectedPatientName = $this->selectedPatient
            ? DB::table('patients')->where('id', $this->selectedPatient)->value('name')
            : null;

        $appointments = DB::table('appointments')
            ->join('patients',    'appointments.patient_id', '=', 'patients.id')
            ->join('doctors',     'appointments.doctor_id',  '=', 'doctors.id')
            ->join('services',    'appointments.service_id', '=', 'services.id')
            ->join('specialties', 'doctors.specialty_id',    '=', 'specialties.id')
            ->select(
                'appointments.*',
                'patients.name  as patient_name',
                'doctors.name   as doctor_name',
                'services.name  as service_name',
                'specialties.name as specialty_name'
            )
            ->when($this->search,    fn($q) => $q->where('patients.name', 'like', "%{$this->search}%"))
            ->when($this->status,    fn($q) => $q->where('appointments.status', $this->status))
            ->when($this->specialty, fn($q) => $q->where('specialties.id', $this->specialty))
            ->orderByDesc('appointments.appointment_date')
            ->paginate(10);

        return view('livewire.appointments.appointment-list', compact(
            'appointments', 'specialties', 'doctors', 'services',
            'patientResults', 'selectedPatientName'
        ))->layout('components.layouts.app', ['title' => 'المواعيد']);
    }
}
