<?php

namespace App\Livewire\Doctors;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Modules\Doctor\Models\Doctor;

class DoctorList extends Component
{
    use WithPagination;

    // ── Filters ───────────────────────────────────────────
    public string $search    = '';
    public string $specialty = '';

    // ── Modal إضافة/تعديل ────────────────────────────────
    public bool   $showModal       = false;
    public ?int   $editId          = null;
    public string $name            = '';
    public ?int   $specialtyId     = null;
    public string $defaultPct      = '';

    // ── Modal حذف ────────────────────────────────────────
    public bool  $showDeleteModal  = false;
    public ?int  $deleteId         = null;
    public string $deleteName      = '';

    public function updatingSearch():    void { $this->resetPage(); }
    public function updatingSpecialty(): void { $this->resetPage(); }

    // ── فتح modal الإضافة ─────────────────────────────────
    public function openCreate(): void
    {
        $this->editId      = null;
        $this->name        = '';
        $this->specialtyId = null;
        $this->defaultPct  = '';
        $this->resetErrorBag();
        $this->showModal   = true;
    }

    // ── فتح modal التعديل ────────────────────────────────
    public function openEdit(int $id): void
    {
        $doctor = Doctor::findOrFail($id);
        $this->editId      = $id;
        $this->name        = $doctor->name;
        $this->specialtyId = $doctor->specialty_id;
        $this->defaultPct  = (string)$doctor->default_percentage;
        $this->resetErrorBag();
        $this->showModal   = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->editId    = null;
        $this->resetErrorBag();
    }

    // ── حفظ (إضافة أو تعديل) ─────────────────────────────
    public function save(): void
    {
        $this->validate([
            'name'       => 'required|string|max:100',
            'specialtyId'=> 'required|exists:specialties,id',
            'defaultPct' => 'required|numeric|min:0|max:100',
        ], [
            'name.required'        => 'اسم الطبيب مطلوب',
            'specialtyId.required' => 'اختر التخصص',
            'defaultPct.required'  => 'النسبة مطلوبة',
            'defaultPct.min'       => 'النسبة يجب أن تكون بين 0 و 100',
            'defaultPct.max'       => 'النسبة يجب أن تكون بين 0 و 100',
        ]);

        $data = [
            'name'               => $this->name,
            'specialty_id'       => $this->specialtyId,
            'default_percentage' => (float)$this->defaultPct,
        ];

        if ($this->editId) {
            Doctor::findOrFail($this->editId)->update($data);
            session()->flash('success', 'تم تعديل بيانات الطبيب بنجاح ✅');
        } else {
            Doctor::create($data);
            session()->flash('success', 'تم إضافة الطبيب بنجاح ✅');
        }

        $this->closeModal();
        $this->resetPage();
    }

    // ── فتح modal تأكيد الحذف ────────────────────────────
    public function confirmDelete(int $id, string $name): void
    {
        $this->deleteId   = $id;
        $this->deleteName = $name;
        $this->showDeleteModal = true;
    }

    // ── حذف الطبيب (soft delete) ─────────────────────────
    public function deleteDoctor(): void
    {
        $doctor = Doctor::findOrFail($this->deleteId);

        // منع الحذف إذا مرتبط بزيارات
        if ($doctor->visits()->exists()) {
            session()->flash('error', 'لا يمكن حذف الطبيب لأنه مرتبط بزيارات مسجّلة');
            $this->showDeleteModal = false;
            return;
        }

        $doctor->delete(); // soft delete
        session()->flash('success', 'تم أرشفة الطبيب بنجاح');
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->resetPage();
    }

    // ── Render ────────────────────────────────────────────
    public function render()
    {
        $specialties = DB::table('specialties')->orderBy('name')->get();

        $doctors = Doctor::with('specialty')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->specialty, fn($q) => $q->where('specialty_id', $this->specialty))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.doctors.doctor-list', compact('doctors', 'specialties'))
            ->layout('components.layouts.app', ['title' => 'الأطباء']);
    }
}
