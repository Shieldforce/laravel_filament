<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'        => "Admin",
                "description" => "Admin do Sistema",
                "active"      => 1,
                "system"      => 1,
            ],
            [
                'name'        => "User",
                "description" => "Usuário do Sistema",
                "active"      => 1,
                "system"      => 1,
            ],
        ];

        foreach ($users as $user) {
            Role::updateOrCreate([
                'name' => $user["name"],
            ], [
                "description" => $user["description"],
                "active"      => $user["active"],
                "system"      => $user["system"],
            ]);
        }
    }
}
