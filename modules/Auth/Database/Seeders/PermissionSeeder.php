<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $now = now();

        $permissions = [
            ['name' => 'manage-users',      'description' => 'Create, edit and delete users',         'created_at' => $now, 'updated_at' => $now],
            ['name' => 'view-all-files',    'description' => 'View files belonging to any user',       'created_at' => $now, 'updated_at' => $now],
            ['name' => 'delete-any-file',   'description' => 'Delete files belonging to any user',     'created_at' => $now, 'updated_at' => $now],
            ['name' => 'view-activity-logs','description' => 'View the activity log of all users',     'created_at' => $now, 'updated_at' => $now],
            ['name' => 'manage-roles',      'description' => 'Assign and revoke roles and permissions', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('permissions')->insert($permissions);

        // Assign all permissions to admin
        $adminId       = DB::table('roles')->where('name', 'admin')->value('id');
        $managerId     = DB::table('roles')->where('name', 'manager')->value('id');
        $permissionIds = DB::table('permissions')->pluck('id');

        // Admin gets everything
        foreach ($permissionIds as $permId) {
            DB::table('permission_role')->insert([
                'role_id'       => $adminId,
                'permission_id' => $permId,
            ]);
        }

        // Manager gets view-all-files and view-activity-logs only
        $managerPerms = DB::table('permissions')
            ->whereIn('name', ['view-all-files', 'view-activity-logs'])
            ->pluck('id');

        foreach ($managerPerms as $permId) {
            DB::table('permission_role')->insert([
                'role_id'       => $managerId,
                'permission_id' => $permId,
            ]);
        }
    }
}