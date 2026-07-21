<?php

namespace Modules\Auth\Database\Seeders;

use Modules\Auth\Models\Role;
use Modules\Auth\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();

        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('12345678'),
                'role_id'  => $adminRole->id,
            ]
        );
    }
}
