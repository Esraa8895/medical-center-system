<?php

namespace App\Modules\Patient\Repositories;

use App\Modules\Patient\Models\Patient;

class PatientRepository
{
    public function all(string $search = '')
    {
        return Patient::when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15);
    }

    public function findById(int $id): Patient
    {
        return Patient::findOrFail($id);
    }

    public function create(array $data): Patient
    {
        return Patient::create($data);
    }

    public function update(Patient $patient, array $data): Patient
    {
        $patient->update($data);
        return $patient->fresh();
    }

    public function delete(Patient $patient): void
    {
        $patient->delete();
    }
}