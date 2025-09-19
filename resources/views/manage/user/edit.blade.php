@extends('layouts/app')

@section('content')
<div class="container">
    <div class="page-inner">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h2 class="fw-bold mb-3">Kelola Pengguna - Edit User</h2>
                <h6 class="op-7 mb-2">
                    Pengelolaan Daftar Pengguna SiReMon BPS Kabupaten Kediri
                </h6>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">Form Edit User</div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Periksa kembali form anda!</strong>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form action="{{ route('manage.user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input
                                type="text"
                                class="form-control"
                                id="username"
                                name="username"
                                value="{{ old('username', $user->username) }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password (kosongkan jika tidak ingin mengubah)
                            </label>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password">
                        </div>

                        <div class="mb-3">
                            <label for="id_role" class="form-label">Role</label>
                            <select
                                class="form-select"
                                name="id_role"
                                id="id_role"
                                required>
                                @foreach ($roles as $role)
                                <option
                                    value="{{ $role->id }}"
                                    {{ $role->id == old('id_role', $user->id_role) ? 'selected' : '' }}>
                                    {{ ucfirst($role->role) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('manage.user.index') }}" class="btn btn-danger me-2">
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection