<?php

namespace App\Livewire\Payments;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Modules\Payment\Models\Payment;
use App\Modules\Visit\Models\Visit;

class PaymentList extends Component
{
    use WithPagination;

    // ── Filters ───────────────────────────────────────────
    public string $search   = '';
    public string $dateFrom = '';
    public string $dateTo   = '';

    // ── Modal إضافة دفعة ──────────────────────────────────
    public bool   $showModal    = false;
    public ?int   $visitId      = null;
    public float  $amount       = 0;
    public string $notes        = '';
    public float  $visitRemaining = 0; // المتبقي على الزيارة المختارة

    public function updatingSearch():   void { $this->resetPage(); }
    public function updatingDateFrom(): void { $this->resetPage(); }
    public function updatingDateTo():   void { $this->resetPage(); }

    // ── فتح/إغلاق Modal ──────────────────────────────────
    public function openModal(): void
    {
        $this->visitId        = null;
        $this->amount         = 0;
        $this->notes          = '';
        $this->visitRemaining = 0;
        $this->showModal      = true;
        $this->resetErrorBag();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    // عند اختيار زيارة — نجلب المتبقي تلقائياً
    public function updatedVisitId(?int $value): void
    {
        if ($value) {
            $visit = DB::table('visits')
                ->leftJoin(DB::raw(
                    '(SELECT visit_id, SUM(amount) as paid FROM payments WHERE deleted_at IS NULL GROUP BY visit_id) as p'
                ), 'p.visit_id', '=', 'visits.id')
                ->where('visits.id', $value)
                ->selectRaw('visits.total_amount - COALESCE(p.paid, 0) as remaining')
                ->first();

            $this->visitRemaining = $visit?->remaining ?? 0;
            $this->amount         = $this->visitRemaining; // افتراضي: الدفع الكامل
        } else {
            $this->visitRemaining = 0;
            $this->amount         = 0;
        }
    }

    // ── حفظ الدفعة ───────────────────────────────────────
    public function savePayment(): void
    {
        $this->validate([
            'visitId' => 'required|exists:visits,id',
            'amount'  => 'required|numeric|min:1',
        ], [
            'visitId.required' => 'اختاري الزيارة',
            'amount.required'  => 'أدخلي المبلغ',
            'amount.min'       => 'المبلغ لازم يكون أكبر من صفر',
        ]);

        $visit = Visit::findOrFail($this->visitId);

        Payment::create([
            'visit_id'   => $this->visitId,
            'patient_id' => $visit->patient_id,
            'amount'     => $this->amount,
            'notes'      => $this->notes ?: null,
        ]);

        // تحديث paid_cost بالزيارة
        $visit->increment('paid_cost', $this->amount);

        session()->flash('success', 'تم تسجيل الدفعة بنجاح ✅');
        $this->closeModal();
        $this->resetPage();
    }

    // ── حذف دفعة (soft delete) ───────────────────────────
    public function deletePayment(int $id): void
    {
        $payment = Payment::findOrFail($id);

        // نرجع المبلغ من paid_cost قبل الأرشفة
        $visit = Visit::find($payment->visit_id);
        if ($visit) {
            $visit->decrement('paid_cost', $payment->amount);
        }

        $payment->delete(); // soft delete
        session()->flash('success', 'تم أرشفة الدفعة');
    }

    // ── Render ────────────────────────────────────────────
    public function render()
    {
        // الزيارات اللي عليها متبقي — للـ dropdown
        $openVisits = DB::table('visits')
            ->join('patients', 'visits.patient_id', '=', 'patients.id')
            ->join('doctors',  'visits.doctor_id',  '=', 'doctors.id')
            ->leftJoin(DB::raw(
                '(SELECT visit_id, SUM(amount) as paid FROM payments WHERE deleted_at IS NULL GROUP BY visit_id) as p'
            ), 'p.visit_id', '=', 'visits.id')
            ->whereNull('visits.deleted_at')
            ->whereRaw('visits.total_amount - COALESCE(p.paid, 0) > 0')
            ->select(
                'visits.id',
                'visits.total_amount',
                'visits.created_at',
                'patients.name as patient_name',
                'doctors.name  as doctor_name',
                DB::raw('COALESCE(p.paid,0) as remaining')
            )
            ->orderByDesc('visits.created_at')
            ->limit(100)
            ->get();

        // الدفعات
        $payments = DB::table('payments')
            ->join('patients', 'payments.patient_id', '=', 'patients.id')
            ->join('visits',   'payments.visit_id',   '=', 'visits.id')
            ->whereNull('payments.deleted_at')
            ->select(
                'payments.id',
                'payments.amount',
                'payments.notes',
                'payments.created_at',
                'patients.name as patient_name',
                'visits.total_amount'
            )
            ->when($this->search,   fn($q) => $q->where('patients.name', 'like', "%{$this->search}%"))
            ->when($this->dateFrom, fn($q) => $q->whereDate('payments.created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('payments.created_at', '<=', $this->dateTo))
            ->orderByDesc('payments.created_at')
            ->paginate(10);

        // إجمالي المدفوع (حسب الفلتر)
        $totalPaid = DB::table('payments')
            ->whereNull('deleted_at')
            ->when($this->search, fn($q) =>
                $q->join('patients', 'payments.patient_id', '=', 'patients.id')
                  ->where('patients.name', 'like', "%{$this->search}%"))
            ->when($this->dateFrom, fn($q) => $q->whereDate('payments.created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('payments.created_at', '<=', $this->dateTo))
            ->sum('amount');

        return view('livewire.payments.payment-list',
            compact('payments', 'totalPaid', 'openVisits'))
            ->layout('components.layouts.app', ['title' => 'المدفوعات']);
    }
}
