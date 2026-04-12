<?php

namespace App\Modules\VisitService\Repositories;

use App\Modules\VisitService\Models\VisitService;

class VisitServiceRepository
{
    public function create(array $data): VisitService
    {
        return VisitService::create($data);
    }

    public function findById(int $id): VisitService
    {
        return VisitService::with('service')->findOrFail($id);
    }

    public function getByVisit(int $visitId)
    {
        return VisitService::with('service')
            ->where('visit_id', $visitId)
            ->get();
    }

    public function update(VisitService $vs, array $data): VisitService
    {
        $vs->update($data);
        return $vs->fresh('service');
    }

    public function delete(VisitService $vs): void
    {
        $vs->delete();
    }
}