@extends('layouts.app')
@section('stylecss')
<!-- Styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<!-- Or for RTL support -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <!-- Notifikasi Error -->
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Notifikasi Sukses -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h2 class="fw-bold mb-3">Tambah User</h2>
                <h6 class="op-7 mb-2">Menambahkan User Baru</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('manage.user.index') }}" class="btn btn-danger btn-round">Kembali</a>
            </div>
        </div>

        <div class="card card-round">
            <div class="card-body">
                <form action="{{ route('manage.user.store') }}" method="POST">
                    @csrf

                    <!-- Nama -->
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input
                            type="text"
                            name="nama"
                            class="form-control @error('nama') is-invalid @enderror"
                            id="nama"
                            value="{{ old('nama') }}"
                            required
                            placeholder="Masukkan nama lengkap">
                        @error('nama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- NIP Lama -->
                    <div class="mb-3">
                        <label for="nip_lama" class="form-label">NIP Lama</label>
                        <input
                            type="text"
                            name="nip_lama"
                            class="form-control @error('nip_lama') is-invalid @enderror"
                            id="nip_lama"
                            value="{{ old('nip_lama') }}"
                            required
                            placeholder="Masukkan NIP lama">
                        @error('nip_lama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- NIP Baru -->
                    <div class="mb-3">
                        <label for="nip_baru" class="form-label">NIP Baru</label>
                        <input
                            type="text"
                            name="nip_baru"
                            class="form-control @error('nip_baru') is-invalid @enderror"
                            id="nip_baru"
                            value="{{ old('nip_baru') }}"
                            required
                            placeholder="Masukkan NIP baru">
                        @error('nip_baru')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Jabatan -->
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <select
                            name="jabatan"
                            class="form-select @error('jabatan') is-invalid @enderror"
                            id="jabatan"
                            required>
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($jabatans as $jabatan)
                            <option value="{{ $jabatan }}" {{ old('jabatan') == $jabatan ? 'selected' : '' }}>
                                {{ $jabatan }}
                            </option>
                            @endforeach
                        </select>
                        @error('jabatan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Golongan_akhir -->
                    <div class="mb-3">
                        <label for="golongan_akhir" class="form-label">Golongan Akhir</label>
                        <select
                            name="golongan_akhir"
                            class="form-select @error('golongan_akhir') is-invalid @enderror"
                            id="golongan_akhir"
                            required>
                            <option value="">-- Pilih Golongan Akhir --</option>
                            @foreach($golongans as $golongan_akhir)
                            <option value="{{ $golongan_akhir }}" {{ old('golongan_akhir') == $golongan_akhir ? 'selected' : '' }}>
                                {{ $golongan_akhir }}
                            </option>
                            @endforeach
                        </select>
                        @error('golongan_akhir')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Tamat Golongan -->
                    <div class="mb-3">
                        <label for="tamat_gol" class="form-label">Tamat Golongan</label>
                        <input
                            type="date"
                            name="tamat_gol"
                            class="form-control @error('tamat_gol') is-invalid @enderror"
                            id="tamat_gol"
                            value="{{ old('tamat_gol') }}"
                            required>
                        @error('tamat_gol')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Pendidikan -->
                    <div class="mb-3">
                        <label for="pendidikan" class="form-label">Pendidikan</label>
                        <input
                            type="text"
                            name="pendidikan"
                            class="form-control @error('pendidikan') is-invalid @enderror"
                            id="pendidikan"
                            value="{{ old('pendidikan') }}"
                            required
                            placeholder="Masukkan pendidikan terakhir">
                        @error('pendidikan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Tanggal Lulus -->
                    <div class="mb-3">
                        <label for="tanggal_lulus" class="form-label">Tanggal Lulus</label>
                        <input
                            type="date"
                            name="tanggal_lulus"
                            class="form-control @error('tanggal_lulus') is-invalid @enderror"
                            id="tanggal_lulus"
                            value="{{ old('tanggal_lulus') }}"
                            required>
                        @error('tanggal_lulus')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="mb-3">
                        <label class="form-label d-block">Jenis Kelamin</label>
                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input @error('jenis_kelamin') is-invalid @enderror"
                                type="radio"
                                name="jenis_kelamin"
                                id="laki_laki"
                                value="LK"
                                {{ old('jenis_kelamin') == 'Laki-laki' ? 'checked' : '' }}
                                required>
                            <label class="form-check-label" for="laki_laki">Laki-laki</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input @error('jenis_kelamin') is-invalid @enderror"
                                type="radio"
                                name="jenis_kelamin"
                                id="perempuan"
                                value="PR"
                                {{ old('jenis_kelamin') == 'Perempuan' ? 'checked' : '' }}
                                required>
                            <label class="form-check-label" for="perempuan">Perempuan</label>
                        </div>
                        @error('jenis_kelamin')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input
                            type="text"
                            name="username"
                            class="form-control @error('username') is-invalid @enderror"
                            id="username"
                            value="{{ old('username') }}"
                            required
                            autocomplete="off"
                            placeholder="Masukkan username">
                        @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            required
                            placeholder="Masukkan password">
                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="Masukkan email">
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div class="mb-3">
                        <label for="id_role" class="form-label">Role</label>
                        <select
                            name="id_role"
                            id="id_role"
                            class="form-select @error('id_role') is-invalid @enderror"
                            required>
                            <option value="">-- Pilih Role --</option>
                            <option value="1" {{ old('id_role') == 1 ? 'selected' : '' }}>Operator</option>
                            <option value="2" {{ old('id_role') == 2 ? 'selected' : '' }}>Ketua Tim</option>
                            <option value="3" {{ old('id_role') == 3 ? 'selected' : '' }}>Admin/Pimpinan</option>
                        </select>
                        @error('id_role')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#jabatan').select2({
            allowClear: false,
            theme: 'bootstrap'
        });
    });
</script>
@endpush