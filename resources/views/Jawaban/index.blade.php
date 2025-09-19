@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Jawaban User</h3>
                <h6 class="op-7 mb-2">Silahkan pilih form terlebih dahulu</h6>
            </div>
        </div>

        <!-- Pilih Form -->
        <form method="GET" action="{{ route('jawaban.index') }}">
            <div class="row mb-3">
                <div class="col-md-6">
                    <select name="form_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Pilih Form --</option>
                        @foreach($forms as $form)
                        <option value="{{ $form->id }}" {{ request('form_id') == $form->id ? 'selected' : '' }}>
                            {{ $form->title }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        @if(request('form_id') && isset($answers) && count($answers) > 0)
        <div class="mb-3 d-flex justify-content-end">
            <a href="{{ route('jawaban.export.excel', request('form_id')) }}" class="btn btn-success btn-sm">
                Download Excel
            </a>
        </div>
        @endif

        @if(isset($answers) && count($answers) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama User</th>
                        <th>Tanggal Submit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($answers as $index => $ans)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $ans->user->pegawai->nama }}</td>
                        <td>{{ $ans->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <!-- Tombol Detail -->
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $ans->id }}">
                                Detail
                            </button>

                            <!-- Tombol Delete -->
                            <form action="{{ route('jawaban.destroy', $ans->id) }}" method="POST" class="d-inline form-delete">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger btn-delete">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Detail -->
                    <div class="modal fade" id="detailModal{{ $ans->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Jawaban - {{ $ans->user->pegawai->nama }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    @php
                                    $grouped = $ans->details->groupBy(function($item) {
                                    return $item->question->section->title ?? 'Tanpa Section';
                                    });
                                    @endphp

                                    @foreach($grouped as $sectionName => $details)
                                    <h5 class="mt-3 mb-2 text-primary">{{ $sectionName }}</h5>
                                    <div class="ps-3">
                                        @foreach($details as $d)
                                        <p>
                                            <strong>{{ $d->question->text }}</strong><br>
                                            {{ $d->answer_text }}
                                        </p>
                                        <hr>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif(request('form_id'))
        <div class="alert alert-warning">
            Belum ada jawaban untuk form ini.
        </div>
        @endif
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const deleteButtons = document.querySelectorAll(".btn-delete");

        deleteButtons.forEach(button => {
            button.addEventListener("click", function(e) {
                let form = this.closest("form");

                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Data jawaban yang dihapus tidak bisa dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: "success",
        title: "Berhasil",
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif
@endsection