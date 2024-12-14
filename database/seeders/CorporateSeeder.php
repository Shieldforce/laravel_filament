<?php

namespace Database\Seeders;

use App\Models\Corporate;
use Illuminate\Database\Seeder;

class CorporateSeeder extends Seeder
{
    public function run(): void
    {
        Corporate::updateOrCreate([
            "name" => "Corporate 1",
        ], [
            "avatar_url" => "logos/default.png",
            "document"   => "111111111111",
            "phone"      => "21970185540",
            "domain"     => "corp1.projeto.sv",
            "email"      => "email@corp1.projeto.sv",
            "slug"       => "corp1"
        ]);

        Corporate::updateOrCreate([
            "name" => "Corporate 2",
        ], [
            "avatar_url" => "logos/default.png",
            "document"   => "22222222222",
            "phone"      => "21970185540",
            "domain"     => "corp2.projeto.sv",
            "email"      => "email@corp2.projeto.sv",
            "slug"       => "corp2"
        ]);
    }
}
