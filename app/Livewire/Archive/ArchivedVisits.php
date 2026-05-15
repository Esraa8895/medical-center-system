<?php

namespace App\Livewire\Archive;

use Livewire\Component;
use Livewire\WithPagination;
use App\Modules\Visit\Models\Visit;

class ArchivedVisits extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.archive.archived-visits', [

            'visits' => Visit::onlyTrashed()
                ->with(['patient', 'doctor'])
                ->latest('deleted_at')
                ->paginate(10)

        ])->layout('components.layouts.app', [
            'title' => 'أرشيف الزيارات'
        ]);
    }
}
