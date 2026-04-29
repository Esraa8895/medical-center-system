<?php

namespace Database\Seeders;

use App\Core\Enums\RoleEnum;
use App\Modules\Auth\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        // ── إنشاء الأدوار ─────────────────────────────────────
        $roles = [
            RoleEnum::ADMIN->value,
            RoleEnum::RECEPTIONIST->value,
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => 'web'],
            );
        }

        // ── حساب المدير (Admin) ────────────────────────────────
        // البريد:    admin@gmail.com
        // كلمة المرور: Admin1234!
        if (!User::where('email', 'admin@gmail.com')->exists()) {
            $admin = User::create([
                'name'     => 'مدير النظام',
                'email'    => 'admin@gmail.com',
                'password' => bcrypt('Admin1234!'),
            ]);
            $admin->assignRole(RoleEnum::ADMIN->value);
        }

        // ── حساب الاستقبال (Receptionist) ─────────────────────
        // البريد:    reception@gmail.com
        // كلمة المرور: Reception1234!
        if (!User::where('email', 'reception@gmail.com')->exists()) {
            $receptionist = User::create([
                'name'     => 'موظفة الاستقبال',
                'email'    => 'reception@gmail.com',
                'password' => bcrypt('Reception1234!'),
            ]);
            $receptionist->assignRole(RoleEnum::RECEPTIONIST->value);
        }
    }
}
