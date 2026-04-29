<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        // هذا Seeder تجريبي — يضيف أطباء بناءً على التخصصات الموجودة
        // بعد الـ fresh migration، التخصصات تكون مرقّمة من 1 إلى 7

        $specialty = fn(string $name) => DB::table('specialties')->where('name', $name)->value('id');

        $doctors = [
            [
                'name'               => 'د. أحمد الشهاب',
                'specialty_name'     => 'طب الأسنان العام',
                'default_percentage' => 60,
            ],
            [
                'name'               => 'د. سارة النور',
                'specialty_name'     => 'تقويم الأسنان',
                'default_percentage' => 65,
            ],
            [
                'name'               => 'د. محمد علي',
                'specialty_name'     => 'جراحة الفم والوجه',
                'default_percentage' => 70,
            ],
            [
                'name'               => 'د. ريم خالد',
                'specialty_name'     => 'طب أسنان الأطفال',
                'default_percentage' => 60,
            ],
        ];

        foreach ($doctors as $doctor) {
            $specialtyId = $specialty($doctor['specialty_name']);
            if ($specialtyId) {
                DB::table('doctors')->insertOrIgnore([
                    'name'               => $doctor['name'],
                    'specialty_id'       => $specialtyId,
                    'default_percentage' => $doctor['default_percentage'],
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }
        }
    }
}
