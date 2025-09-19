@extends('layouts/app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">List Form</h3>
                <h6 class="op-7 mb-2">Pilih Form Yang Sudah Dibuat</h6>
            </div>
        </div>
        <div class="list-group">
            @forelse($forms as $form)
            <div class="list-group-item d-flex justify-content-between align-items-center mb-2">
                <!-- Judul form tanpa link -->
                <span>{{ $form->title }}</span>

                <div class="btn-group ms-auto">
                    @php
                    $sudahIsi = $form->answers->where('user_id', Auth::id())->count() > 0;
                    @endphp

                    @if(!$sudahIsi)
                    <!-- Tombol Isi Form (jika belum pernah isi) -->
                    <a href="{{ route('form.show', $form->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-pen"></i> Isi Form
                    </a>
                    @else
                    <!-- Tombol Disabled + SweetAlert -->
                    <button type="button" class="btn btn-sm btn-secondary btn-isi-disabled" data-form="{{ $form->title }}">
                        <i class="fas fa-ban"></i> Isi Form
                    </button>
                    @endif

                    @if(Auth::user()->id_role == 3)
                    <!-- Tombol Edit -->
                    <a href="{{ route('form.edit', $form->id) }}" class="btn btn-sm btn-warning ms-1">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <!-- Tombol Delete -->
                    <form action="{{ route('form.destroy', $form->id) }}" method="POST" class="delete-form ms-1">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger btn-delete">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="alert alert-info">
                Belum ada form tersedia.
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Konfirmasi Delete
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                let form = this.closest('form');
                Swal.fire({
                    title: 'Yakin mau hapus form ini?',
                    text: "Data form akan terhapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Feedback sukses
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000
        });
        @endif

        // Feedback isi form yang sudah ada
        document.querySelectorAll('.btn-isi-disabled').forEach(button => {
            button.addEventListener('click', function() {
                let formTitle = this.getAttribute('data-form');
                Swal.fire({
                    icon: 'warning',
                    title: 'Form sudah diisi',
                    text: `Anda sudah mengisi form "${formTitle}". Silakan hubungi admin untuk menghapus jika ingin mengisi ulang.`,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            });
        });
    });
</script>
@endsection