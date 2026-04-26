<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Modules\CenterExpense\Services\CenterExpenseService;

class ReportDashboard extends Component
{
    public string $dateFrom    = '';
    public string $dateTo      = '';
    public ?int   $specialtyId = null;  // ← فلتر التخصص

    public function mount(): void
    {
        $this->dateFrom = now()->startOfMonth()->toDateString();
        $this->dateTo   = now()->toDateString();
    }

    public function updatingDateFrom():    void {}
    public function updatingDateTo():      void {}
    public function updatingSpecialtyId(): void {}

    public function render()
    {
        // ── إحصائيات عامة ─────────────────────────────────────────────
        $totalPatients = DB::table('patients')->whereNull('deleted_at')->count();
        $totalVisits   = DB::table('visits')->whereNull('deleted_at')->count();
        $totalDoctors  = DB::table('doctors')->count();

        // ── vsQuery مع specialty filter ───────────────────────────────
        $vsQuery = DB::table('visit_services')
            ->join('visits',  'visit_services.visit_id', '=', 'visits.id')
            ->join('doctors', 'visits.doctor_id',        '=', 'doctors.id')
            ->whereNull('visit_services.deleted_at')
            ->whereNull('visits.deleted_at')
            ->when($this->dateFrom,    fn($q) => $q->whereDate('visits.created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,      fn($q) => $q->whereDate('visits.created_at', '<=', $this->dateTo))
            ->when($this->specialtyId, fn($q) => $q->where('doctors.specialty_id', $this->specialtyId));

        $totalRevenue = (clone $vsQuery)->sum(DB::raw('visit_services.price - visit_services.discount'));
        $totalClinic  = (clone $vsQuery)->sum('visit_services.clinic_share');

        // ── المحصّل فعلياً ────────────────────────────────────────────
        $totalPaid = DB::table('payments')
            ->join('visits',  'payments.visit_id',  '=', 'visits.id')
            ->join('doctors', 'visits.doctor_id',   '=', 'doctors.id')
            ->whereNull('payments.deleted_at')
            ->when($this->dateFrom,    fn($q) => $q->whereDate('payments.created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,      fn($q) => $q->whereDate('payments.created_at', '<=', $this->dateTo))
            ->when($this->specialtyId, fn($q) => $q->where('doctors.specialty_id', $this->specialtyId))
            ->sum('payments.amount');

        // ── حصص الأطباء ──────────────────────────────────────────────
        $doctorShares = DB::table('visit_services')
            ->join('visits',  'visit_services.visit_id', '=', 'visits.id')
            ->join('doctors', 'visits.doctor_id',        '=', 'doctors.id')
            ->whereNull('visit_services.deleted_at')
            ->whereNull('visits.deleted_at')
            ->when($this->dateFrom,    fn($q) => $q->whereDate('visits.created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,      fn($q) => $q->whereDate('visits.created_at', '<=', $this->dateTo))
            ->when($this->specialtyId, fn($q) => $q->where('doctors.specialty_id', $this->specialtyId))
            ->select(
                'doctors.name',
                DB::raw('doctors.default_percentage as percentage'),
                DB::raw('SUM(visit_services.doctor_share) as total_share')
            )
            ->groupBy('doctors.id', 'doctors.name', 'doctors.default_percentage')
            ->orderByDesc('total_share')
            ->get();

        // ── تكاليف المركز ─────────────────────────────────────────────
        $centerExpenses = app(CenterExpenseService::class)
            ->totalBetween($this->dateFrom, $this->dateTo);

        // ── صافي الربح الفعلي ─────────────────────────────────────────
        $netProfit = $totalClinic - $centerExpenses;

        // ── آخر الزيارات ──────────────────────────────────────────────
        $recentVisits = DB::table('visits')
            ->join('patients',    'visits.patient_id',     '=', 'patients.id')
            ->join('doctors',     'visits.doctor_id',      '=', 'doctors.id')
            ->join('specialties', 'doctors.specialty_id',  '=', 'specialties.id')
            ->whereNull('visits.deleted_at')
            ->when($this->dateFrom,    fn($q) => $q->whereDate('visits.created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,      fn($q) => $q->whereDate('visits.created_at', '<=', $this->dateTo))
            ->when($this->specialtyId, fn($q) => $q->where('doctors.specialty_id', $this->specialtyId))
            ->select(
                'visits.id',
                'visits.created_at',
                'visits.total_amount',
                'patients.name  as patient_name',
                'doctors.name   as doctor_name',
                'specialties.name as specialty_name'
            )
            ->orderByDesc('visits.created_at')
            ->limit(10)
            ->get();

        // ── قائمة التخصصات للـ dropdown ──────────────────────────────
        $specialties = DB::table('specialties')->orderBy('name')->get();

        return view('livewire.reports.report-dashboard', compact(
            'totalPatients', 'totalVisits',   'totalDoctors',
            'totalRevenue',  'totalPaid',     'totalClinic',
            'doctorShares',  'recentVisits',
            'centerExpenses','netProfit',
            'specialties'
        ))->layout('components.layouts.app', ['title' => 'التقارير']);
    }
}
