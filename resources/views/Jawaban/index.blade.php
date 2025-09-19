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
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $ans->id }}">
                                Detail
                            </button>
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
                                    @foreach($ans->details as $d)
                                    <p>
                                        <strong>{{ $d->question->text }}</strong><br>
                                        {{ $d->answer_text }}
                                    </p>
                                    <hr>
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