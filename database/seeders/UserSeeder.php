<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => "SA",
                'email'    => "admin@admin.com",
                'password' => bcrypt(env("ADMIN_SEEDER_PASSWORD")),
                "system"   => 1,
                "roles"    => [1]
            ],
            [
                'name'     => "User",
                'email'    => "user@user.com",
                'password' => bcrypt(env("ADMIN_SEEDER_PASSWORD")),
                "system"   => 1,
                "roles"    => [2]
            ],
        ];

        foreach ($users as $user) {
            $userCreate = User::updateOrCreate([
                'name'  => $user["name"],
                'email' => $user["email"],
            ], [
                'password' => $user["password"],
                'system'   => $user["system"],
            ]);

            $userCreate->roles()->sync($user["roles"]);
        }
    }
}
