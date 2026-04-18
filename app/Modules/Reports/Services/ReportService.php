<?php

namespace App\Modules\Reports\Services;

use Illuminate\Support\Facades\DB;

class ReportService
{
    // ─── تقرير مالي عام ────────────────────────────────────────────────────────
    // إجمالي الإيرادات + إجمالي حصة الأطباء + إجمالي أرباح العيادة (SYP فقط)
    public function financialReport(?string $dateFrom, ?string $dateTo): array
    {
        $vsQuery = DB::table('visit_services')
            ->join('visits', 'visit_services.visit_id', '=', 'visits.id')
            ->when($dateFrom, fn($q) => $q->whereDate('visits.created_at', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('visits.created_at', '<=', $dateTo));

        $revenue      = (clone $vsQuery)->sum(DB::raw('visit_services.price - visit_services.discount'));
        $doctorTotal  = (clone $vsQuery)->sum('visit_services.doctor_share');
        $clinicTotal  = (clone $vsQuery)->sum('visit_services.clinic_share');

        $paidQuery = DB::table('payments')
            ->join('visits', 'payments.visit_id', '=', 'visits.id')
            ->when($dateFrom, fn($q) => $q->whereDate('payments.created_at', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('payments.created_at', '<=', $dateTo));

        $totalPaid = (clone $paidQuery)->sum('payments.amount');

        return [
            'total_revenue'    => round($revenue, 2),
            'total_paid'       => round($totalPaid, 2),
            'doctor_shares'    => round($doctorTotal, 2),
            'clinic_profit'    => round($clinicTotal, 2),
            'currency'         => 'SYP',
            'date_from'        => $dateFrom,
            'date_to'          => $dateTo,
        ];
    }

    // ─── أرباح العيادة حسب الطبيب ──────────────────────────────────────────────
    public function clinicProfitReport(?string $dateFrom, ?string $dateTo): array
    {
        $rows = DB::table('visit_services')
            ->join('visits',  'visit_services.visit_id',  '=', 'visits.id')
            ->join('doctors', 'visits.doctor_id',         '=', 'doctors.id')
            ->when($dateFrom, fn($q) => $q->whereDate('visits.created_at', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('visits.created_at', '<=', $dateTo))
            ->select(
                'doctors.id as doctor_id',
                'doctors.name as doctor_name',
                DB::raw('SUM(visit_services.price - visit_services.discount) as total_revenue'),
                DB::raw('SUM(visit_services.doctor_share)                    as doctor_share'),
                DB::raw('SUM(visit_services.clinic_share)                    as clinic_share'),
                DB::raw('COUNT(DISTINCT visits.id)                           as visits_count')
            )
            ->groupBy('doctors.id', 'doctors.name')
            ->orderByDesc('clinic_share')
            ->get();

        return [
            'currency' => 'SYP',
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
            'doctors'   => $rows,
        ];
    }

    // ─── تقرير مريضة ───────────────────────────────────────────────────────────
    public function patientReport(int $patientId, ?string $dateFrom, ?string $dateTo): array
    {
        $patient = DB::table('patients')->find($patientId);

        $visits = DB::table('visits')
            ->join('doctors', 'visits.doctor_id', '=', 'doctors.id')
            ->where('visits.patient_id', $patientId)
            ->when($dateFrom, fn($q) => $q->whereDate('visits.created_at', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('visits.created_at', '<=', $dateTo))
            ->select(
                'visits.id',
                'visits.created_at',
                'visits.total_amount',
                'visits.paid_cost',
                'doctors.name as doctor_name'
            )
            ->orderByDesc('visits.created_at')
            ->get();

        $totalPaid = DB::table('payments')
            ->join('visits', 'payments.visit_id', '=', 'visits.id')
            ->where('visits.patient_id', $patientId)
            ->when($dateFrom, fn($q) => $q->whereDate('payments.created_at', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('payments.created_at', '<=', $dateTo))
            ->sum('payments.amount');

        $totalAmount = $visits->sum('total_amount');

        return [
            'patient'      => $patient,
            'visits'       => $visits,
            'total_amount' => round($totalAmount, 2),
            'total_paid'   => round($totalPaid, 2),
            'balance'      => round($totalAmount - $totalPaid, 2),
            'currency'     => 'SYP',
            'date_from'    => $dateFrom,
            'date_to'      => $dateTo,
        ];
    }

    // ─── تقرير طبيب ────────────────────────────────────────────────────────────
    public function doctorReport(int $doctorId, ?string $dateFrom, ?string $dateTo): array
    {
        $doctor = DB::table('doctors')->find($doctorId);

        $summary = DB::table('visit_services')
            ->join('visits', 'visit_services.visit_id', '=', 'visits.id')
            ->where('visits.doctor_id', $doctorId)
            ->when($dateFrom, fn($q) => $q->whereDate('visits.created_at', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('visits.created_at', '<=', $dateTo))
            ->selectRaw('
                SUM(visit_services.price - visit_services.discount) as total_revenue,
                SUM(visit_services.doctor_share)                    as doctor_share,
                SUM(visit_services.clinic_share)                    as clinic_share,
                COUNT(DISTINCT visits.id)                           as visits_count
            ')
            ->first();

        $recentVisits = DB::table('visits')
            ->join('patients', 'visits.patient_id', '=', 'patients.id')
            ->where('visits.doctor_id', $doctorId)
            ->when($dateFrom, fn($q) => $q->whereDate('visits.created_at', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('visits.created_at', '<=', $dateTo))
            ->select('visits.id', 'visits.created_at', 'visits.total_amount', 'patients.name as patient_name')
            ->orderByDesc('visits.created_at')
            ->limit(20)
            ->get();

        return [
            'doctor'        => $doctor,
            'total_revenue' => round($summary->total_revenue ?? 0, 2),
            'doctor_share'  => round($summary->doctor_share  ?? 0, 2),
            'clinic_share'  => round($summary->clinic_share  ?? 0, 2),
            'visits_count'  => $summary->visits_count ?? 0,
            'recent_visits' => $recentVisits,
            'currency'      => 'SYP',
            'date_from'     => $dateFrom,
            'date_to'       => $dateTo,
        ];
    }
}
