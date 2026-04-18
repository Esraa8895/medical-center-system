<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ReportDashboard extends Component
{
    public string $dateFrom = '';
    public string $dateTo   = '';

    public function updatingDateFrom(): void {}
    public function updatingDateTo(): void {}

    public function render()
    {
        // ── إحصائيات عامة ──────────────────────────────────────────────────────
        $totalPatients = DB::table('patients')->count();
        $totalVisits   = DB::table('visits')->count();
        $totalDoctors  = DB::table('doctors')->count();

        // ── إجمالي الإيرادات SYP (من visit_services مع فلترة تاريخ) ───────────
        $vsQuery = DB::table('visit_services')
            ->join('visits', 'visit_services.visit_id', '=', 'visits.id')
            ->when($this->dateFrom, fn($q) => $q->whereDate('visits.created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('visits.created_at', '<=', $this->dateTo));

        $totalRevenue  = (clone $vsQuery)->sum(DB::raw('visit_services.price - visit_services.discount'));
        $totalDoctors_ = (clone $vsQuery)->sum('visit_services.doctor_share');
        $totalClinic   = (clone $vsQuery)->sum('visit_services.clinic_share');

        // ── المبلغ المحصَّل فعلياً ──────────────────────────────────────────────
        $totalPaid = DB::table('payments')
            ->join('visits', 'payments.visit_id', '=', 'visits.id')
            ->when($this->dateFrom, fn($q) => $q->whereDate('payments.created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('payments.created_at', '<=', $this->dateTo))
            ->sum('payments.amount');

        // ── حصص الأطباء ────────────────────────────────────────────────────────
        $doctorShares = DB::table('visit_services')
            ->join('visits',  'visit_services.visit_id', '=', 'visits.id')
            ->join('doctors', 'visits.doctor_id',        '=', 'doctors.id')
            ->when($this->dateFrom, fn($q) => $q->whereDate('visits.created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('visits.created_at', '<=', $this->dateTo))
            ->select('doctors.name', DB::raw('SUM(visit_services.doctor_share) as total_share'))
            ->groupBy('doctors.id', 'doctors.name')
            ->orderByDesc('total_share')
            ->get();

        // ── آخر الزيارات مع المبلغ الصحيح من visits.total_amount ───────────────
        $recentVisits = DB::table('visits')
            ->join('patients', 'visits.patient_id', '=', 'patients.id')
            ->join('doctors',  'visits.doctor_id',  '=', 'doctors.id')
            ->when($this->dateFrom, fn($q) => $q->whereDate('visits.created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('visits.created_at', '<=', $this->dateTo))
            ->select(
                'visits.id',
                'visits.created_at',
                'visits.total_amount',
                'patients.name as patient_name',
                'doctors.name  as doctor_name'
            )
            ->orderByDesc('visits.created_at')
            ->limit(10)
            ->get();

        return view('livewire.reports.report-dashboard', compact(
            'totalPatients', 'totalVisits', 'totalDoctors',
            'totalRevenue',  'totalPaid',   'totalClinic',
            'doctorShares',  'recentVisits'
        ))->layout('components.layouts.app', ['title' => 'التقارير']);
    }
}
