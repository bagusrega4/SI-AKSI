<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel users sebelum insert
        DB::table('users')->delete();

        $filePath = database_path('seeders/data/users.json');

        if (!File::exists($filePath)) {
            $this->command->error("File users.json tidak ditemukan.");
            return;
        }

        $jsonData = File::get($filePath);
        $userList = json_decode($jsonData, true);

        if ($userList === null) {
            $this->command->error("Gagal decode JSON. Pastikan format JSON valid.");
            return;
        }

        foreach ($userList as $user) {
            User::create([
                'id'        => $user['id'],
                'nip_lama'  => $user['nip_lama'],
                'username'  => $user['username'],
                'password'  => bcrypt('password123'),
                'email'     => $user['email'],
                'id_role'   => $user['id_role'],
                'tim_id'    => $user['tim_id'] ?? null, // biarkan null kalau tidak ada
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info("Data users berhasil diimpor dari JSON.");
    }
}
