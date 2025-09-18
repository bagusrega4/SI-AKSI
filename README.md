# Dokumentasi Struktur Proyek

## Struktur Folder dan File

### 1. `app`
Berisi logika utama aplikasi, termasuk controller, model, dan middleware.
- **Tujuan:** Menyimpan kode utama aplikasi.

### 2. `bootstrap`
Berisi file untuk bootstrap aplikasi.
- **Tujuan:** Menginisialisasi framework dan memuat file konfigurasi.

### 3. `config`
Berisi file konfigurasi untuk aplikasi.
- **Tujuan:** Memberikan pengaturan spesifik lingkungan seperti koneksi database dan caching.

### 4. `database`
Berisi migrasi dan seeder database.
- **Tujuan:** Mengelola skema database dan data uji.

### 5. `public`
Berisi file yang dapat diakses publik seperti gambar, CSS, dan JavaScript.
- **Tujuan:** Menyediakan aset statis untuk aplikasi.

### 6. `resources`
Berisi file view, aset mentah, dan file bahasa.
- **Tujuan:** Menyimpan sumber daya seperti template Blade dan file lokalisasi.

### 7. `routes`
Mendefinisikan semua rute untuk aplikasi.
- **Tujuan:** Memetakan URL ke controller dan metode.

### 8. `storage`
Digunakan untuk menyimpan log, template yang dikompilasi, dan file unggahan.
- **Tujuan:** Menyimpan file sementara dan file yang diunggah pengguna.

### 9. `stubs`
Berisi file stub kustom untuk scaffolding.
- **Tujuan:** Menyesuaikan template scaffolding.

### 10. `tests`
Berisi kasus uji untuk aplikasi.
- **Tujuan:** Memastikan kualitas dan kebenaran kode.

---

## File Utama

### 1. `.editorconfig`
- **Tujuan:** Memastikan gaya penulisan kode yang konsisten di berbagai editor.

### 2. `.env.example`
- **Tujuan:** Memberikan template untuk variabel lingkungan.

### 3. `composer.json`
- **Tujuan:** Mengelola dependensi PHP.

### 4. `package.json`
- **Tujuan:** Mengelola dependensi Node.js.

### 5. `README.md`
- **Tujuan:** Memberikan gambaran umum dan instruksi untuk proyek.

---

## Dokumen

### 1. `Alih_Hak_Kelompok3.pdf`
- **Tujuan:** Dokumentasi untuk alih hak.

### 2. `BAST_Kelompok3.pdf`
- **Tujuan:** Berisi BAST, perjanjian transfer, dan panduan.

### 3. `Laporan Milestone_4_Kelompok3.pdf`
- **Tujuan:** Laporan Milestone 4.

### 4. `Buku Panduan Aplikasi BDA.pdf`
- **Tujuan:** Panduan pengguna untuk aplikasi BDA.

---

## Catatan
- Pastikan untuk memperbarui file `.env` dengan konfigurasi spesifik lingkungan.
- Ikuti standar penulisan kode yang ditentukan dalam `.editorconfig`.
- Gunakan `composer install` dan `npm install` untuk mengatur dependensi sebelum menjalankan aplikasi.

---
