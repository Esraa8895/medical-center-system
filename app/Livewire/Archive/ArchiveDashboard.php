<?php

namespace App\Livewire\Archive;

use Livewire\Component;
use App\Modules\Patient\Models\Patient;
use App\Modules\Appointment\Models\Appointment;
use App\Modules\Visit\Models\Visit;
use App\Modules\Payment\Models\Payment;

class ArchiveDashboard extends Component
{
    public function render()
    {
        return view('livewire.archive.archive-dashboard', [

            'patientsCount'     => Patient::onlyTrashed()->count(),

            'appointmentsCount' => Appointment::onlyTrashed()->count(),

            'visitsCount'       => Visit::onlyTrashed()->count(),

            'paymentsCount'     => Payment::onlyTrashed()->count(),

        ])->layout('components.layouts.app', [
            'title' => 'الأرشيف'
        ]);
    }
}
