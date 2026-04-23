<?php

namespace App\Modules\Specialization\Services;

use App\Modules\Specialization\Models\Specialty;
use Illuminate\Database\Eloquent\Collection;

class SpecialtyService
{
    public function getAllSpecialties(): Collection
    {
        return Specialty::all();
    }

    public function createSpecialty(array $data): Specialty
    {
        return Specialty::create($data);
    }

    public function updateSpecialty(Specialty $specialty, array $data): Specialty
    {
        $specialty->update($data);
        return $specialty->fresh();
    }

    public function deleteSpecialty(Specialty $specialty): void
    {
        $specialty->delete();
    }
}
