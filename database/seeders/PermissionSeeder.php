<?php

namespace Database\Seeders;

use App\Services\PermissionGenerateService;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissionGenerate = new PermissionGenerateService();
        $permissionGenerate->handle();
    }
}
