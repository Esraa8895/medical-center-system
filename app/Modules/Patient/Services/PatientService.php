<?php

namespace App\Modules\Patient\Services;

use App\Modules\Patient\Models\Patient;
use App\Modules\Patient\Repositories\PatientRepository;

class PatientService
{
    public function __construct(
        private PatientRepository $repo
    ) {}

    public function getAll(string $search = '')
    {
        return $this->repo->all($search);
    }

    public function getById(int $id): Patient
    {
        return $this->repo->findById($id);
    }

    public function create(array $data): Patient
    {
        return $this->repo->create($data);
    }

    public function update(int $id, array $data): Patient
    {
        $patient = $this->repo->findById($id);
        return $this->repo->update($patient, $data);
    }

    public function delete(int $id): void
    {
        $patient = $this->repo->findById($id);
        $this->repo->delete($patient);
    }

    public function getAppointments(int $id)
    {
        return $this->repo->findById($id)
            ->appointments()
            ->with(['doctor', 'service'])
            ->latest()
            ->get();
    }

    public function getVisits(int $id)
    {
        return $this->repo->findById($id)
            ->visits()
            ->with(['doctor', 'visitServices.service'])
            ->latest()
            ->get();
    }
}