<?php

namespace App\Livewire\Visits;

use Livewire\Component;
use Livewire\WithPagination;
use App\Modules\Visit\Models\Visit;

class VisitList extends Component
{
    use WithPagination;

    public function render()
    {
        $visits = Visit::with(['patient', 'doctor'])
            ->latest()
            ->paginate(10);

        return view('livewire.visits.visit-list', compact('visits'))
            ->layout('components.layouts.app', ['title' => 'الزيارات']);
    }
}