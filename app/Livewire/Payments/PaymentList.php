<?php

namespace App\Livewire\Payments;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class PaymentList extends Component
{
    use WithPagination;

    public string $currency = '';

    public function updatingCurrency(): void { $this->resetPage(); }

    public function render()
    {
        $payments = DB::table('payments')
            ->join('patients', 'payments.patient_id', '=', 'patients.id')
            ->join('visits', 'payments.visit_id', '=', 'visits.id')
            ->select('payments.*', 'patients.name as patient_name')
            ->when($this->currency, fn($q) => $q->where('payments.currency', $this->currency))
            ->orderByDesc('payments.created_at')
            ->paginate(10);

        $totals = DB::table('payments')
            ->select('currency', DB::raw('SUM(amount) as total'))
            ->groupBy('currency')
            ->get();

        return view('livewire.payments.payment-list', compact('payments', 'totals'))
            ->layout('components.layouts.app', ['title' => 'المدفوعات']);
    }
}