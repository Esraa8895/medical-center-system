<?php

namespace App\Modules\Reports\Http\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Core\Traits\ApiResponseTrait;
use App\Modules\Reports\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private ReportService $reportService) {}

    // GET /reports/financial?date_from=2026-01-01&date_to=2026-12-31
    public function financial(Request $request)
    {
        $data = $this->reportService->financialReport(
            $request->query('date_from'),
            $request->query('date_to')
        );
        return $this->successResponse($data);
    }

    // GET /reports/clinic-profit?date_from=...&date_to=...
    public function clinicProfit(Request $request)
    {
        $data = $this->reportService->clinicProfitReport(
            $request->query('date_from'),
            $request->query('date_to')
        );
        return $this->successResponse($data);
    }

    // GET /reports/patient/{id}?date_from=...&date_to=...
    public function patient(Request $request, int $id)
    {
        $data = $this->reportService->patientReport(
            $id,
            $request->query('date_from'),
            $request->query('date_to')
        );
        return $this->successResponse($data);
    }

    // GET /reports/doctor/{id}?date_from=...&date_to=...
    public function doctor(Request $request, int $id)
    {
        $data = $this->reportService->doctorReport(
            $id,
            $request->query('date_from'),
            $request->query('date_to')
        );
        return $this->successResponse($data);
    }
}
