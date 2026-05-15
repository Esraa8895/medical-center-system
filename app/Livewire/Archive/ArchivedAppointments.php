<?php

namespace App\Livewire\Archive;

use Livewire\Component;
use Livewire\WithPagination;
use App\Modules\Appointment\Models\Appointment;

class ArchivedAppointments extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.archive.archived-appointments', [

            'appointments' => Appointment::onlyTrashed()
                ->with(['patient', 'doctor', 'service'])
                ->latest('deleted_at')
                ->paginate(10)

        ])->layout('components.layouts.app', [
            'title' => 'أرشيف المواعيد'
        ]);
    }
}
