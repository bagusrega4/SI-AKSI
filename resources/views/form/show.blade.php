@extends('layouts/app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">{{ $form->title }}</h3>
            </div>
        </div>

        <form action="{{ route('form.storeAnswer', $form->id) }}"
            method="POST"
            onsubmit="return validateAnswers(event)">
            @csrf
            <input type="hidden" name="form_id" value="{{ $form->id }}">
            @foreach($form->sections as $section)
            <div class="card mb-4">
                <div class="card-header">
                    <strong>{{ $section->title }}</strong>
                    @if($section->description)
                    <p class="mb-0 text-muted small">{{ $section->description }}</p>
                    @endif
                </div>
                <div class="card-body">
                    @foreach($section->questions as $qIndex => $question)
                    <div class="mb-3">
                        <label class="fw-bold">{{ ($qIndex+1) . '. ' . $question->text }}</label>

                        {{-- Simpan section_id agar terkirim --}}
                        <input type="hidden" name="sections[{{ $question->id }}]" value="{{ $section->id }}">

                        {{-- Input Text --}}
                        @if($question->type === 'text')
                        <input type="text"
                            name="answers[{{ $question->id }}]"
                            class="form-control">

                        {{-- Pilihan Ganda --}}
                        @elseif($question->type === 'multiple')
                        @foreach($question->options as $opt)
                        <div class="form-check">
                            <input type="radio"
                                name="answers[{{ $question->id }}]"
                                value="{{ $opt->option_text }}"
                                class="form-check-input"
                                id="q{{ $question->id }}_{{ $loop->index }}">
                            <label class="form-check-label" for="q{{ $question->id }}_{{ $loop->index }}">
                                {{ $opt->option_text }}
                            </label>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <button type="submit" class="btn btn-success">Kirim Jawaban</button>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000
        });
        @elseif(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: "{{ session('error') }}",
            confirmButtonText: 'OK'
        });
        @endif
    });
</script>

<script>
    function validateAnswers(event) {
        let errors = [];

        // Cek semua input text
        const textInputs = document.querySelectorAll('input[type="text"][name^="answers"]');
        for (let input of textInputs) {
            if (input.value.trim() === "") {
                const questionDiv = input.closest('.mb-3');
                const label = questionDiv.querySelector('label.fw-bold');
                let qNumber = "No. ?";
                if (label) {
                    const match = label.textContent.trim().match(/^(\d+)\./);
                    if (match) qNumber = `No. ${match[1]}`;
                }

                const sectionCard = input.closest('.card');
                const sectionTitle = sectionCard.querySelector('.card-header strong')?.textContent.trim() || "Section";

                errors.push(`Section: ${sectionTitle}, ${qNumber}`);
            }
        }

        // Cek semua radio group
        const radioGroups = {};
        document.querySelectorAll('input[type="radio"][name^="answers"]').forEach(radio => {
            if (!radioGroups[radio.name]) {
                radioGroups[radio.name] = [];
            }
            radioGroups[radio.name].push(radio);
        });

        for (let groupName in radioGroups) {
            const group = radioGroups[groupName];
            const checked = group.some(radio => radio.checked);
            if (!checked) {
                const questionDiv = group[0].closest('.mb-3');
                const label = questionDiv.querySelector('label.fw-bold');
                let qNumber = "No. ?";
                if (label) {
                    const match = label.textContent.trim().match(/^(\d+)\./);
                    if (match) qNumber = `No. ${match[1]}`;
                }

                const sectionCard = group[0].closest('.card');
                const sectionTitle = sectionCard.querySelector('.card-header strong')?.textContent.trim() || "Section";

                errors.push(`Section: ${sectionTitle}, ${qNumber}`);
            }
        }

        // Kalau ada error → tampilkan semuanya sekaligus
        if (errors.length > 0) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Ada pertanyaan yang belum diisi',
                html: errors.map(e => `<div>${e}</div>`).join(""),
                confirmButtonColor: '#d33'
            });
            return false;
        }

        return true; // semua terisi → submit jalan
    }
</script>
@endsection