<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name'        => "Admin",
                "description" => "Admin do Sistema",
                "active"      => 1,
                "system"      => 1,
                "permissions" => []
            ],
            [
                'name'        => "User",
                "description" => "Usuário do Sistema",
                "active"      => 1,
                "system"      => 1,
                "permissions" => Permission::where("default", 1)->pluck("id")->toArray()
            ],
        ];

        foreach ($roles as $role) {
            $roleCreate = Role::updateOrCreate([
                'name' => $role["name"],
            ], [
                "description" => $role["description"],
                "active"      => $role["active"],
                "system"      => $role["system"],
            ]);

            $roleCreate->permissions()->syncWithoutDetaching($role["permissions"]);
        }
    }
}
