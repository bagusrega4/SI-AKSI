<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('seeders/data/Pegawai.json');

        if (!File::exists($filePath)) {
            $this->command->error("File Pegawai.json tidak ditemukan.");
            return;
        }

        $jsonData = File::get($filePath);
        $pegawaiList = json_decode($jsonData, true);

        if ($pegawaiList === null) {
            $this->command->error("Gagal decode JSON. Pastikan format JSON valid.");
            return;
        }

        foreach ($pegawaiList as $pegawai) {
            DB::table('pegawai')->insert([
                'id'             => $pegawai['id'],
                'nama'           => $pegawai['nama'],
                'nip_lama'       => $pegawai['nip_lama'],
                'nip_baru'       => $pegawai['nip_baru'],
                'jabatan'        => $pegawai['jabatan'],
                'golongan_akhir' => $pegawai['golongan_akhir'],
                'tamat_gol'      => $this->convertDate($pegawai['tamat_gol'] ?? null),
                'pendidikan'     => $pegawai['pendidikan'],
                'tanggal_lulus'  => $this->convertDate($pegawai['tanggal_lulus'] ?? null),
                'jenis_kelamin'  => $pegawai['jenis_kelamin'],
                'email'          => $pegawai['email'],
            ]);
        }

        $this->command->info("Data pegawai berhasil diimpor dari JSON.");
    }

    private function convertDate(?string $date)
    {
        if (!$date) return null;
        try {
            // asumsinya format dari JSON: DD-MM-YYYY
            return Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null; // kalau gagal parsing, jadikan null biar tidak error
        }
    }
}
