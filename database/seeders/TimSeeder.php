<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Tim;

class TimSeeder extends Seeder
{
    public function run(): void
    {
        // Path file JSON
        $filePath = database_path('seeders/data/tim.json');

        if (!File::exists($filePath)) {
            $this->command->error("File tim.json tidak ditemukan di $filePath.");
            return;
        }

        // Baca JSON
        $data = json_decode(File::get($filePath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error("Format JSON tidak valid: " . json_last_error_msg());
            return;
        }

        foreach ($data as $row) {
            $namaTim = $row['tim'] ?? null;

            if ($namaTim) {
                Tim::firstOrCreate([
                    'nama_tim' => $namaTim
                ]);
            }
        }

        $this->command->info("Data tim berhasil diimpor dari tim.json.");
    }
}
