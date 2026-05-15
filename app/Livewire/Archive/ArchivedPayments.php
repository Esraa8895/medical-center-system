<?php

namespace App\Livewire\Archive;

use Livewire\Component;
use Livewire\WithPagination;
use App\Modules\Payment\Models\Payment;

class ArchivedPayments extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.archive.archived-payments', [

            'payments' => Payment::onlyTrashed()
                ->with(['patient', 'visit'])
                ->latest('deleted_at')
                ->paginate(10)

        ])->layout('components.layouts.app', [
            'title' => 'أرشيف المدفوعات'
        ]);
    }
}
