<?php

namespace App\Modules\Doctor\Services;

use App\Modules\Doctor\Models\Doctor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class DoctorService
{
    public function getAllDoctors(): Collection
    {
        return Doctor::with('specialty')->get();
    }

    public function createDoctor(array $data): Doctor
    {
        $this->validatePercentage($data['default_percentage']);

        return DB::transaction(function () use ($data) {
            return Doctor::create($data);
        });
    }

    public function updateDoctor(Doctor $doctor, array $data): Doctor
    {
        if (isset($data['default_percentage'])) {
            $this->validatePercentage($data['default_percentage']);
        }

        return DB::transaction(function () use ($doctor, $data) {
            $doctor->update($data);
            return $doctor->fresh();
        });
    }

    public function deleteDoctor(Doctor $doctor): void
    {
        if ($doctor->visits()->exists()) {
            throw new Exception("لا يمكن حذف الدكتور لأنه مرتبط ببيانات");
        }

        $doctor->delete();
    }

    private function validatePercentage($percentage): void
    {
        if ($percentage < 0 || $percentage > 100) {
            throw new Exception("نسبة الدكتور يجب أن تكون بين 0 و 100");
        }
    }
}
