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
                'name'     => 'طب الأسنان العام',
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
                'name'     => 'تقويم الأسنان',
                'services' => [
                    ['name' => 'كشف تقويم', 'price' => 10000],
                    ['name' => 'تركيب تقويم ثابت', 'price' => 300000],
                    ['name' => 'تركيب تقويم شفاف', 'price' => 500000],
                    ['name' => 'زيارة متابعة تقويم', 'price' => 20000],
                    ['name' => 'خلع تقويم ومثبت', 'price' => 50000],
                ],
            ],
            [
                'name'     => 'طب أسنان الأطفال',
                'services' => [
                    ['name' => 'كشف أطفال', 'price' => 5000],
                    ['name' => 'حشو حليبي', 'price' => 15000],
                    ['name' => 'قلع سن حليبي', 'price' => 10000],
                    ['name' => 'فلورايد', 'price' => 10000],
                    ['name' => 'سيلانت (طلاء وقائي)', 'price' => 15000],
                ],
            ],
            [
                'name'     => 'جراحة الفم والوجه',
                'services' => [
                    ['name' => 'كشف جراحي', 'price' => 10000],
                    ['name' => 'استئصال كيس', 'price' => 100000],
                    ['name' => 'زراعة سن (implant)', 'price' => 600000],
                    ['name' => 'رفع جيب جيبي', 'price' => 120000],
                ],
            ],
            [
                'name'     => 'طب اللثة',
                'services' => [
                    ['name' => 'كشف لثة', 'price' => 5000],
                    ['name' => 'علاج التهاب اللثة', 'price' => 30000],
                    ['name' => 'كيور لثة', 'price' => 60000],
                    ['name' => 'جراحة لثة', 'price' => 150000],
                ],
            ],
            [
                'name'     => 'تركيبات وتيجان',
                'services' => [
                    ['name' => 'كشف تركيبات', 'price' => 5000],
                    ['name' => 'تاج زيركون', 'price' => 120000],
                    ['name' => 'تاج معدني خزفي', 'price' => 80000],
                    ['name' => 'طقم أسنان كامل', 'price' => 250000],
                    ['name' => 'جسر ثلاثي', 'price' => 220000],
                    ['name' => 'فينير', 'price' => 100000],
                ],
            ],
            [
                'name'     => 'علاج جذور (أعصاب)',
                'services' => [
                    ['name' => 'كشف عصب', 'price' => 5000],
                    ['name' => 'معالجة لبية (ضرس أمامي)', 'price' => 40000],
                    ['name' => 'معالجة لبية (ضرس خلفي)', 'price' => 70000],
                    ['name' => 'إعادة معالجة لبية', 'price' => 90000],
                ],
            ],
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
