<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class DailyView extends Component
{
    public string $date        = '';
    public ?int   $specialtyId = null;

    public function mount(): void
    {
        $this->date = now()->toDateString();
    }

    public function render()
    {
        $specialties = DB::table('specialties')->orderBy('name')->get();

        $rows = DB::table('visits')
            ->join('patients',       'visits.patient_id',         '=', 'patients.id')
            ->join('doctors',        'visits.doctor_id',          '=', 'doctors.id')
            ->join('specialties',    'doctors.specialty_id',      '=', 'specialties.id')
            ->join('visit_services', 'visit_services.visit_id',   '=', 'visits.id')
            ->join('services',       'visit_services.service_id', '=', 'services.id')
            ->leftJoin(DB::raw(
                '(SELECT visit_id, SUM(amount) as paid
                  FROM payments
                  WHERE deleted_at IS NULL
                  GROUP BY visit_id) as p'
            ), 'p.visit_id', '=', 'visits.id')
            ->whereNull('visits.deleted_at')
            ->whereNull('visit_services.deleted_at')
            ->when($this->date,
                fn($q) => $q->whereDate('visits.created_at', $this->date))
            ->when($this->specialtyId,
                fn($q) => $q->where('doctors.specialty_id', $this->specialtyId))
            ->select(
                'visits.id                                                    as visit_id',
                'patients.name                                                as patient_name',
                'services.name                                                as service_name',
                'doctors.name                                                 as doctor_name',
                'specialties.name                                             as specialty_name',
                DB::raw('visit_services.price - visit_services.discount       as total'),
                DB::raw('COALESCE(p.paid, 0)                                  as paid'),
                DB::raw('(visit_services.price - visit_services.discount)
                         - COALESCE(p.paid, 0)                                as remaining'),
                'visit_services.discount',
                'visits.created_at                                            as visit_time'
            )
            ->orderByDesc('visits.created_at')
            ->get();

        $dayTotal     = $rows->sum('total');
        $dayPaid      = $rows->sum('paid');
        $dayRemaining = $rows->sum('remaining');
        $dayCount     = $rows->count();

        return view('livewire.admin.daily-view',
            compact('rows', 'specialties', 'dayTotal', 'dayPaid', 'dayRemaining', 'dayCount'))
            ->layout('components.layouts.app', ['title' => 'اليومية']);
    }
}
