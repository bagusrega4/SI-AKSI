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
                <h2 class="fw-bold mb-3">Kelola Pengguna</h2>
                <h6 class="op-7 mb-2">Pengelolaan Daftar Pengguna SiReMon BPS Kabupaten Kediri</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('manage.user.create') }}" class="btn btn-primary btn-round">Tambah User</a>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">Daftar Pengguna</div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>NIP Lama</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Tim</th> {{-- Kolom baru --}}
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $user->nip_lama }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <button
                                            class="btn btn-{{ $user->role->role == 'admin' ? 'warning' : ($user->role->role == 'keuangan' ? 'secondary' : 'primary') }} dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown">
                                            {{ ucfirst($user->role->role) }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach($roles as $role)
                                            @if ($role->role != $user->role->role)
                                            <li>
                                                <button
                                                    class="dropdown-item"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#confirmModal"
                                                    data-id="{{ $user->id }}"
                                                    data-role-id="{{ $role->id }}"
                                                    data-role-name="{{ $role->role }}"
                                                    data-username="{{ $user->username }}">
                                                    {{ ucfirst($role->role) }}
                                                </button>
                                            </li>
                                            @endif
                                            @endforeach
                                        </ul>
                                    </td>

                                    {{-- Kolom Tim --}}
                                    <td>
                                        @if ($user->id_role == 2 || $user->id_role == 3)
                                        <button
                                            class="btn btn-outline-success btn-sm dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown">
                                            {{ $user->tim ? $user->tim->nama_tim : 'Pilih Tim' }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach($timList as $tim)
                                            @if (!$user->tim || $user->tim->id !== $tim->id)
                                            <li>
                                                <button
                                                    class="dropdown-item"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#confirmTimModal"
                                                    data-id="{{ $user->id }}"
                                                    data-tim-id="{{ $tim->id }}"
                                                    data-tim-name="{{ $tim->nama_tim }}"
                                                    data-username="{{ $user->username }}">
                                                    {{ $tim->nama_tim }}
                                                </button>
                                            </li>
                                            @endif
                                            @endforeach
                                        </ul>
                                        @else
                                        -
                                        @endif
                                    </td>

                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('manage.user.edit', $user->id) }}"
                                                class="btn btn-info btn-sm me-2">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModalCenter-{{ $user->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Ubah Role -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Ubah Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin mengubah role untuk user <b><span id="modal-username"></span></b> menjadi <b><span id="modal-new-role"></span></b>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <form id="modalForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_role" id="modal-role-id">
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const confirmModal = document.getElementById('confirmModal');
        const modalForm = confirmModal.querySelector('#modalForm');
        const modalUsername = confirmModal.querySelector('#modal-username');
        const modalNewRole = confirmModal.querySelector('#modal-new-role');
        const modalRoleId = confirmModal.querySelector('#modal-role-id');

        const updateRoleRoutePattern = "{{ route('manage.user.updateRole', ':id') }}";

        confirmModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const roleId = button.getAttribute('data-role-id');
            const roleName = button.getAttribute('data-role-name');
            const username = button.getAttribute('data-username');

            modalUsername.textContent = username;
            modalNewRole.textContent = roleName.charAt(0).toUpperCase() + roleName.slice(1);
            modalRoleId.value = roleId;
            modalForm.action = updateRoleRoutePattern.replace(':id', id);
        });
    });
</script>
@endsection

<!-- Modal Delete -->
@section('modal-delete')
@foreach($users as $user)
<div class="modal fade" id="deleteModalCenter-{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel-{{ $user->id }}">Konfirmasi Hapus User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus user <b>{{ $user->username }}</b>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('manage.user.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(number).replace(/\s+/g, "");
    }

    document.addEventListener('DOMContentLoaded', function() {
        const nominalElements = document.querySelectorAll('.nominal-currency');
        nominalElements.forEach(element => {
            const rawValue = element.textContent;
            element.textContent = formatRupiah(rawValue);
        });
    });
</script>
@endpush

<!-- Modal Konfirmasi Ubah Tim -->
<div class="modal fade" id="confirmTimModal" tabindex="-1" aria-labelledby="confirmTimModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Ubah Tim</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin mengubah tim untuk user <b><span id="modal-username-tim"></span></b> menjadi <b><span id="modal-new-tim"></span></b>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <form id="modalTimForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="tim_id" id="modal-tim-id">
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const confirmTimModal = document.getElementById('confirmTimModal');
        const modalTimForm = confirmTimModal.querySelector('#modalTimForm');
        const modalUsernameTim = confirmTimModal.querySelector('#modal-username-tim');
        const modalNewTim = confirmTimModal.querySelector('#modal-new-tim');
        const modalTimId = confirmTimModal.querySelector('#modal-tim-id');

        const updateTimRoutePattern = "{{ route('manage.user.updateTim', ':id') }}";

        confirmTimModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const timId = button.getAttribute('data-tim-id');
            const timName = button.getAttribute('data-tim-name');
            const username = button.getAttribute('data-username');

            modalUsernameTim.textContent = username;
            modalNewTim.textContent = timName;
            modalTimId.value = timId;
            modalTimForm.action = updateTimRoutePattern.replace(':id', id);
        });
    });
</script>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: "{{ session('error') }}"
        });
        @endif
    });
</script>
@endpush