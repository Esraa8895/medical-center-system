<?php

namespace App\Livewire\TreatmentPlans;

use Livewire\Component;
use Livewire\WithPagination;
use App\Modules\TreatmentPlan\Models\TreatmentPlan;

class TreatmentPlanList extends Component
{
    use WithPagination;

    public function render()
    {
        $plans = TreatmentPlan::with(['patient', 'doctor', 'service'])
            ->latest()
            ->paginate(10);

        return view('livewire.treatment-plans.treatment-plan-list', compact('plans'))
            ->layout('components.layouts.app', ['title' => 'خطط العلاج']);
    }
}