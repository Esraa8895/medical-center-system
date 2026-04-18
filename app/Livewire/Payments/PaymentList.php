<?php

namespace App\Livewire\Payments;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class PaymentList extends Component
{
    use WithPagination;

    public function render()
    {
        $payments = DB::table('payments')
            ->join('patients', 'payments.patient_id', '=', 'patients.id')
            ->join('visits',   'payments.visit_id',   '=', 'visits.id')
            ->select('payments.*', 'patients.name as patient_name')
            ->orderByDesc('payments.created_at')
            ->paginate(10);

        $totalPaid = DB::table('payments')->sum('amount');

        return view('livewire.payments.payment-list', compact('payments', 'totalPaid'))
            ->layout('components.layouts.app', ['title' => 'المدفوعات']);
    }
}
