<?php

namespace App\Modules\TreatmentPlan\Repositories;

use App\Modules\TreatmentPlan\Models\TreatmentPlan;

class TreatmentPlanRepository
{
    public function all()
    {
        return TreatmentPlan::with(['patient', 'doctor', 'service'])
            ->latest()
            ->paginate(15);
    }

    public function findById(int $id): TreatmentPlan
    {
        return TreatmentPlan::with(['patient', 'doctor', 'service'])
            ->findOrFail($id);
    }

    public function create(array $data): TreatmentPlan
    {
        return TreatmentPlan::create($data);
    }

    public function update(TreatmentPlan $plan, array $data): TreatmentPlan
    {
        $plan->update($data);
        return $plan->fresh(['patient', 'doctor', 'service']);
    }

    public function delete(TreatmentPlan $plan): void
    {
        $plan->delete();
    }
}