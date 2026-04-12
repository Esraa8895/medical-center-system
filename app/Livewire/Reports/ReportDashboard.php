<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ReportDashboard extends Component
{
    public function render()
    {
        $totalPatients = DB::table('patients')->count();
        $totalVisits   = DB::table('visits')->count();
        $totalDoctors  = DB::table('doctors')->count();

        $revenueByCurrency = DB::table('payments')
            ->select('currency', DB::raw('SUM(amount) as total'))
            ->groupBy('currency')
            ->get();

        $doctorShares = DB::table('visit_services')
            ->join('visits', 'visit_services.visit_id', '=', 'visits.id')
            ->join('doctors', 'visits.doctor_id', '=', 'doctors.id')
            ->select('doctors.name', DB::raw('SUM(visit_services.doctor_share) as total_share'))
            ->groupBy('doctors.id', 'doctors.name')
            ->orderByDesc('total_share')
            ->get();

        $recentVisits = DB::table('visits')
            ->join('patients', 'visits.patient_id', '=', 'patients.id')
            ->join('doctors', 'visits.doctor_id', '=', 'doctors.id')
            ->select('visits.*', 'patients.name as patient_name', 'doctors.name as doctor_name')
            ->orderByDesc('visits.created_at')
            ->limit(5)
            ->get();

            return view('livewire.reports.report-dashboard', compact(
                'totalPatients', 'totalVisits', 'totalDoctors',
                'revenueByCurrency', 'doctorShares', 'recentVisits'
            ))->layout('components.layouts.app', ['title' => 'التقارير']);
    }
}