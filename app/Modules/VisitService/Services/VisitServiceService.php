<?php

namespace App\Modules\VisitService\Services;

use App\Modules\VisitService\Models\VisitService;
use App\Modules\VisitService\Repositories\VisitServiceRepository;
use App\Modules\Visit\Models\Visit;
use App\Modules\Doctor\Models\Doctor;
use App\Modules\Service\Models\Service;

class VisitServiceService
{
    public function __construct(
        private VisitServiceRepository $repo
    ) {}

    public function getByVisit(int $visitId)
    {
        return $this->repo->getByVisit($visitId);
    }

    public function create(array $data): VisitService
    {
        $data = $this->calcShares($data);
        $vs = $this->repo->create($data);
        $this->recalcVisitTotal($data['visit_id']);
        return $vs;
    }

    public function update(int $id, array $data): VisitService
    {
        $vs = $this->repo->findById($id);
        $data = $this->calcShares(array_merge($vs->toArray(), $data));
        $updated = $this->repo->update($vs, $data);
        $this->recalcVisitTotal($vs->visit_id);
        return $updated;
    }

    public function delete(int $id): void
    {
        $vs = $this->repo->findById($id);
        $visitId = $vs->visit_id;
        $this->repo->delete($vs);
        $this->recalcVisitTotal($visitId);
    }

    private function calcShares(array $data): array
    {
        // جلب نسبة الطبيب من Doctor إذا ما تحددت
        if (empty($data['doctor_percentage']) && !empty($data['visit_id'])) {
            $visit = Visit::find($data['visit_id']);
            if ($visit) {
                $doctor = Doctor::find($visit->doctor_id);
                $data['doctor_percentage'] = $doctor?->default_percentage ?? 0;
            }
        }

        $price             = $data['price'] ?? 0;
        $discount          = $data['discount'] ?? 0;
        $doctorPercentage  = $data['doctor_percentage'] ?? 0;

        $netPrice                = $price - $discount;
        $data['doctor_share']    = round($netPrice * ($doctorPercentage / 100), 2);
        $data['clinic_share']    = round($netPrice - $data['doctor_share'], 2);
        $data['clinic_percentage'] = round(100 - $doctorPercentage, 2);

        return $data;
    }

    private function recalcVisitTotal(int $visitId): void
    {
        $visit = Visit::find($visitId);
        if (!$visit) return;

        $total = VisitService::where('visit_id', $visitId)
            ->selectRaw('SUM(price - discount) as total')
            ->value('total') ?? 0;

        $visit->update(['total_amount' => $total]);
    }
}