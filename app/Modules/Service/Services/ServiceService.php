<?php

namespace App\Modules\Service\Services;

use App\Modules\Service\Models\Service;
use App\Modules\Specialization\Models\Specialty;
use App\Modules\VisitService\Models\VisitService as VisitServiceModel;
use App\Modules\TreatmentPlan\Models\TreatmentPlan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Exception;

class ServiceService
{
    public function getAll(string $search = '', int $perPage = 15): LengthAwarePaginator
    {
        $search = $this->normalizeSearchTerm($search);

      return Service::with('specialty')
    ->when(!empty($search), function ($query) use ($search) {
        $query->where(function ($query) use ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhereHas('specialty', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                });
        });
    })
    ->latest()
    ->paginate($perPage);
    }

    public function search(string $search, int $perPage = 15): LengthAwarePaginator
    {
        return $this->getAll($search, $perPage);
    }

    public function getById(int $id): Service
    {
        return Service::with('specialty')->findOrFail($id);
    }

    public function create(array $data): Service
    {
        $data = $this->sanitizeData($data);
        $this->ensureSpecialtyExists($data['specialty_id'] ?? null);

        return DB::transaction(function () use ($data) {
            return Service::create($data);
        });
    }

    public function update(int $id, array $data): Service
    {
        $service = $this->getById($id);
        $data = $this->sanitizeData($data, $service);

        if (array_key_exists('specialty_id', $data)) {
            $this->ensureSpecialtyExists($data['specialty_id']);
        }

        return DB::transaction(function () use ($service, $data) {
            $service->update($data);
            return $service->fresh();
        });
    }

    public function delete(int $id): void
    {
        $service = $this->getById($id);

        if ($this->isUsedByVisitServices($service->id)) {
            throw new Exception('لا يمكن حذف الخدمة لأنها مستخدمة في خدمات الزيارة.');
        }

        if ($this->isUsedByTreatmentPlans($service->id)) {
            throw new Exception('لا يمكن حذف الخدمة لأنها مستخدمة في خطط العلاج.');
        }

        $service->delete();
    }

    private function normalizeSearchTerm(string $search): string
    {
        return trim(mb_strtolower(preg_replace('/\s+/', ' ', $search)));
    }

    private function sanitizeData(array $data, ?Service $service = null): array
    {
        $result = [];

        if (array_key_exists('name', $data)) {
            $result['name'] = trim((string) $data['name']);
        }

        if (array_key_exists('specialty_id', $data)) {
            $result['specialty_id'] = is_numeric($data['specialty_id']) ? (int) $data['specialty_id'] : null;
        }

        if (array_key_exists('price', $data)) {
            $result['price'] = $this->normalizePrice($data['price']);
        }

        if (!$service) {
            if (empty($result['name'])) {
                throw new Exception('اسم الخدمة مطلوب.');
            }

            if (empty($result['specialty_id'])) {
                throw new Exception('التخصص مطلوب.');
            }

            if (!isset($result['price'])) {
                $result['price'] = 0;
            }
        }

        return $result;
    }

    private function normalizePrice($price): float
    {
        if (is_string($price)) {
            $price = str_replace([',', ' '], ['', ''], $price);
        }

        if (!is_numeric($price)) {
            throw new Exception('السعر غير صالح.');
        }

        return round((float) $price, 2);
    }

    private function ensureSpecialtyExists(?int $specialtyId): void
    {
        if (!$specialtyId || !Specialty::find($specialtyId)) {
            throw new Exception('التخصص المحدد غير موجود.');
        }
    }

    private function isUsedByVisitServices(int $serviceId): bool
    {
        return VisitServiceModel::where('service_id', $serviceId)->exists();
    }

    private function isUsedByTreatmentPlans(int $serviceId): bool
    {
        return TreatmentPlan::where('service_id', $serviceId)->exists();
    }
}

