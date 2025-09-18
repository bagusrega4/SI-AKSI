<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\FormPengajuan;
use App\Models\Komponen;
use App\Models\KRO;
use App\Models\SubKomponen;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(PegawaiSeeder::class);
        $this->call(TimSeeder::class);
        $this->call(UserSeeder::class);
    }
}
