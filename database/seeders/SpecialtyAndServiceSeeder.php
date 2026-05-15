<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialtyAndServiceSeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            [
                'name'     => 'أسنان',
                'services' => [
                    ['name' => 'كشف وفحص أسنان', 'price' => 5000],
                    ['name' => 'تنظيف الأسنان', 'price' => 15000],
                    ['name' => 'حشو ضرس (composit)', 'price' => 25000],
                    ['name' => 'حشو إبري', 'price' => 35000],
                    ['name' => 'قلع ضرس عادي', 'price' => 15000],
                    ['name' => 'قلع ضرس عقل', 'price' => 40000],
                    ['name' => 'تبييض أسنان', 'price' => 80000],
                ],
            ],
            [
                'name'     => 'جلدية',
                'services' => [
                    ['name' => 'كشف تقويم', 'price' => 10000],
                    ['name' => 'تركيب تقويم ثابت', 'price' => 300000],
                    ['name' => 'تركيب تقويم شفاف', 'price' => 500000],
                    ['name' => 'زيارة متابعة تقويم', 'price' => 20000],
                    ['name' => 'خلع تقويم ومثبت', 'price' => 50000],
                ],
            ],
            [
                'name'     => 'نسائية',
                'services' => [
                    ['name' => 'كشف أطفال', 'price' => 5000],
                    ['name' => 'حشو حليبي', 'price' => 15000],
                    ['name' => 'قلع سن حليبي', 'price' => 10000],
                    ['name' => 'فلورايد', 'price' => 10000],
                    ['name' => 'سيلانت (طلاء وقائي)', 'price' => 15000],
                ],
            ]
        ];
        
        foreach ($specialties as $specialtyData) {
            $services = $specialtyData['services'];
            unset($specialtyData['services']);

            $specialtyId = DB::table('specialties')->insertGetId([
                'name'       => $specialtyData['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($services as $service) {
                DB::table('services')->insert([
                    'name'         => $service['name'],
                    'specialty_id' => $specialtyId,
                    'price'        => $service['price'],
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }
        }
    }
}
