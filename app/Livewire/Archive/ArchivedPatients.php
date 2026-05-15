<?php

namespace App\Livewire\Archive;

use Livewire\Component;
use Livewire\WithPagination;
use App\Modules\Patient\Models\Patient;

class ArchivedPatients extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.archive.archived-patients', [

            'patients' => Patient::onlyTrashed()
                ->latest('deleted_at')
                ->paginate(10)

        ])->layout('components.layouts.app', [
            'title' => 'أرشيف المرضى'
        ]);
    }
}
