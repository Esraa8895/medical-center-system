<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndAdminSeeder::class,        // 1. الأدوار + حسابات الدخول
            SpecialtyAndServiceSeeder::class,  // 2. التخصصات والخدمات
            DoctorSeeder::class,               // 3. أطباء تجريبيون (اختياري)
        ]);
    }
}
