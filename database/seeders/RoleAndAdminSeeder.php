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
        $roles = [
            RoleEnum::ADMIN->value,
            RoleEnum::RECEPTIONIST->value,
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => 'web'],
            );
        }

        if (!User::where('email', 'admin@gmail.com')->exists()) {
            $admin = User::create([
                'name'     => 'Admin',
                'email'    => 'admin@gmail.com',
                'password' => bcrypt('Admin1234!'),
            ]);

            $admin->assignRole(RoleEnum::ADMIN->value);
        }
    }
}
