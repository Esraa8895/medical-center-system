<?php

namespace App\Modules\Visit\Services;

use App\Modules\Visit\Models\Visit;
use App\Modules\Visit\Repositories\VisitRepository;
use App\Modules\TreatmentPlan\Models\TreatmentPlan;

class VisitService
{
    public function __construct(
        private VisitRepository $repo
    ) {}

    public function getAll()
    {
        return $this->repo->all();
    }

    public function getById(int $id): Visit
    {
        return $this->repo->findById($id);
    }

    public function create(array $data): Visit
    {
        $visit = $this->repo->create($data);

        // زيادة completed_sessions تلقائياً
        if (!empty($data['treatment_plan_id'])) {
            $plan = TreatmentPlan::find($data['treatment_plan_id']);
            if ($plan) {
                $plan->increment('completed_sessions');
                // تحديث status إذا اكتملت الجلسات
                if ($plan->total_sessions && $plan->completed_sessions >= $plan->total_sessions) {
                    $plan->update(['status' => 'completed']);
                }
            }
        }

        return $visit;
    }
}
