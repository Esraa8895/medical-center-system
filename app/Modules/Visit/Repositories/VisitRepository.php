<?php

namespace App\Modules\Visit\Repositories;

use App\Modules\Visit\Models\Visit;

class VisitRepository
{
    public function all()
    {
        return Visit::with(['patient', 'doctor', 'visitServices.service'])
            ->latest()
            ->paginate(15);
    }

    public function findById(int $id): Visit
    {
        return Visit::with(['patient', 'doctor', 'visitServices.service', 'payments'])
            ->findOrFail($id);
    }

    public function create(array $data): Visit
    {
        return Visit::create($data);
    }

    public function update(Visit $visit, array $data): Visit
    {
        $visit->update($data);
        return $visit->fresh(['patient', 'doctor', 'visitServices.service']);
    }
}
