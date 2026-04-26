<?php

namespace App\Modules\Appointment\Services;

use App\Modules\Appointment\Models\Appointment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Exception;

class AppointmentService
{
    public function getAll(string $search = '', int $perPage = 15): LengthAwarePaginator
    {
        return Appointment::with(['patient', 'doctor', 'service'])
            ->when($search, function ($query) use ($search) {
                $search = strtolower(trim($search));

                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(type) LIKE ?', ["%{$search}%"])
                      ->orWhereRaw('LOWER(status) LIKE ?', ["%{$search}%"])
                      ->orWhereRaw('LOWER(notes) LIKE ?', ["%{$search}%"])
                      ->orWhereHas('patient', fn($q) => $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]))
                      ->orWhereHas('doctor', fn($q) => $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]))
                      ->orWhereHas('service', fn($q) => $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]));
                });
            })
            ->orderByDesc('appointment_date')
            ->paginate($perPage);
    }

    public function getById(int $id): Appointment
    {
        return Appointment::with(['patient', 'doctor', 'service'])->findOrFail($id);
    }

    public function create(array $data): Appointment
    {
        $data['appointment_date'] = $this->formatDate($data['appointment_date']);
        $data['type']   = $data['type']   ?? 'first_visit';
        $data['status'] = $data['status'] ?? 'scheduled';

        // منع تضارب بسيط (نفس الوقت)
        $this->checkConflict($data);

        return DB::transaction(function () use ($data) {
            return Appointment::create($data);
        });
    }

    public function update(int $id, array $data): Appointment
    {
        $appointment = $this->getById($id);

        if (isset($data['appointment_date'])) {
            $data['appointment_date'] = $this->formatDate($data['appointment_date']);
        }

        if (isset($data['status']) && $data['status'] === 'scheduled') {
            $this->checkConflict($data, $appointment->id);
        }

        return DB::transaction(function () use ($appointment, $data) {
            $appointment->update($data);
            return $appointment->fresh(['patient', 'doctor', 'service']);
        });
    }


    public function delete(int $id): void
    {
        $appointment = $this->getById($id);

        if ($appointment->status === 'completed') {
            throw new Exception('لا يمكن حذف موعد مكتمل.');
        }

        $appointment->delete();
    }


    private function formatDate($date): string
    {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }

    private function checkConflict(array $data, ?int $ignoreId = null): void
    {
        if (empty($data['appointment_date']) || empty($data['doctor_id'])) {
            return;
        }

        $query = Appointment::where('appointment_date', $data['appointment_date'])
            ->where('doctor_id', $data['doctor_id'])
            ->whereIn('status', ['scheduled', 'completed']);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        if ($query->exists()) {
            throw new Exception('الطبيب لديه موعد بنفس الوقت.');
        }
    }


    public function getBySpecialty(int $specialtyId, string $search = '', ?string $date = null, int $perPage = 15)
{
    return Appointment::query()
        ->select(
            'appointments.*',
            'patients.name as patient_name',
            'doctors.name as doctor_name',
            'services.name as service_name',
            'specialties.name as specialty_name'
        )
        ->join('patients', 'appointments.patient_id', '=', 'patients.id')
        ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
        ->join('services', 'appointments.service_id', '=', 'services.id')
        ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')

        ->where('doctors.specialty_id', $specialtyId)

        ->when($date, function ($query) use ($date) {
            $query->whereDate('appointments.appointment_date', $date);
        })

        ->when($search, function ($query) use ($search) {
            $search = strtolower(trim($search));

            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(appointments.type) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(appointments.status) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(patients.name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(doctors.name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(services.name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(specialties.name) LIKE ?', ["%{$search}%"]);
            });
        })

        ->orderBy('appointments.appointment_date')
        ->paginate($perPage);
}
}
