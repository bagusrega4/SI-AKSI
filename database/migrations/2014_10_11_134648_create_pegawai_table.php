<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('nip_lama', 20)->unique();
            $table->string('nip_baru', 20)->unique();
            $table->string('jabatan', 100);
            $table->string('golongan_akhir', 20);
            $table->date('tamat_gol')->nullable();
            $table->string('pendidikan', 100)->nullable();
            $table->date('tanggal_lulus')->nullable();
            $table->enum('jenis_kelamin', ['LK', 'PR']);
            $table->string('email', 150)->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
