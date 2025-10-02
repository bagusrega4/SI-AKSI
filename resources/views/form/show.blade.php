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
                        
                        @if($question->type === 'checkbox')
                            @php
                                $minText = $question->min_selections ? "minimal {$question->min_selections}" : "";
                                $maxText = $question->max_selections ? "maksimal {$question->max_selections}" : "";
                                $separator = ($minText && $maxText) ? " dan " : "";
                                $limitText = $minText . $separator . $maxText;
                            @endphp
                            
                            @if($limitText)
                            <small class="text-danger d-block mb-2">
                                <i class="fas fa-exclamation-circle"></i> Pilih {{ $limitText }} pilihan
                            </small>
                            @endif
                        @endif

                        {{-- Simpan section_id agar terkirim --}}
                        <input type="hidden" name="sections[{{ $question->id }}]" value="{{ $section->id }}">

                        {{-- Input Text --}}
                        @if($question->type === 'text')
                        <input type="text"
                            name="answers[{{ $question->id }}]"
                            class="form-control"
                            placeholder="Masukkan jawaban Anda">

                        {{-- Pilihan Ganda (Radio) --}}
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

                        {{-- Checkbox (Pilih Banyak) --}}
                        @elseif($question->type === 'checkbox')
                        @foreach($question->options as $opt)
                        <div class="form-check">
                            <input type="checkbox"
                                name="answers[{{ $question->id }}][]"
                                value="{{ $opt->option_text }}"
                                class="form-check-input checkbox-question"
                                id="q{{ $question->id }}_{{ $loop->index }}"
                                data-question="{{ $question->id }}"
                                data-min-selections="{{ $question->min_selections ?? '' }}"
                                data-max-selections="{{ $question->max_selections ?? '' }}"
                                onchange="checkSelectionLimits(this)">
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
    // Real-time validation untuk checkbox dengan min/max
    function checkSelectionLimits(checkbox) {
        const questionId = checkbox.getAttribute('data-question');
        const minSelections = parseInt(checkbox.getAttribute('data-min-selections'));
        const maxSelections = parseInt(checkbox.getAttribute('data-max-selections'));
        
        const checkboxes = document.querySelectorAll(`input[data-question="${questionId}"]`);
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        
        // Validasi maksimal - unchecked otomatis jika melebihi
        if (maxSelections && checkedCount > maxSelections) {
            checkbox.checked = false;
            
            Swal.fire({
                icon: 'warning',
                title: 'Batas Maksimal Terlampaui',
                text: `Anda hanya boleh memilih maksimal ${maxSelections} pilihan!`,
                confirmButtonColor: '#f39c12',
                timer: 2000,
                timerProgressBar: true
            });
        }
    }

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

                errors.push(`Section: ${sectionTitle}, ${qNumber} - belum diisi`);
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

                errors.push(`Section: ${sectionTitle}, ${qNumber} - belum dipilih`);
            }
        }

        // Cek semua checkbox group
        const checkboxGroups = {};
        document.querySelectorAll('input[type="checkbox"].checkbox-question').forEach(checkbox => {
            const questionId = checkbox.getAttribute('data-question');
            if (!checkboxGroups[questionId]) {
                checkboxGroups[questionId] = [];
            }
            checkboxGroups[questionId].push(checkbox);
        });

        for (let questionId in checkboxGroups) {
            const group = checkboxGroups[questionId];
            const checkedBoxes = group.filter(checkbox => checkbox.checked);
            const minSelections = parseInt(group[0].getAttribute('data-min-selections'));
            const maxSelections = parseInt(group[0].getAttribute('data-max-selections'));

            const questionDiv = group[0].closest('.mb-3');
            const label = questionDiv.querySelector('label.fw-bold');
            let qNumber = "No. ?";
            if (label) {
                const match = label.textContent.trim().match(/^(\d+)\./);
                if (match) qNumber = `No. ${match[1]}`;
            }

            const sectionCard = group[0].closest('.card');
            const sectionTitle = sectionCard.querySelector('.card-header strong')?.textContent.trim() || "Section";

            // Validasi: jika ada min, harus memenuhi minimal
            if (minSelections && checkedBoxes.length < minSelections) {
                errors.push(`Section: ${sectionTitle}, ${qNumber} - minimal pilih ${minSelections} pilihan (terpilih: ${checkedBoxes.length})`);
            }
            // Validasi: jika tidak ada min tapi ada max, minimal 1 harus dipilih
            else if (!minSelections && checkedBoxes.length === 0) {
                errors.push(`Section: ${sectionTitle}, ${qNumber} - belum ada yang dipilih`);
            }

            // Validasi: tidak boleh lebih dari max_selections
            if (maxSelections && checkedBoxes.length > maxSelections) {
                errors.push(`Section: ${sectionTitle}, ${qNumber} - maksimal ${maxSelections} pilihan (terpilih: ${checkedBoxes.length})`);
            }
        }

        // Kalau ada error → tampilkan semuanya sekaligus
        if (errors.length > 0) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: '<div style="text-align: left;">' + errors.map(e => `<div class="mb-1">• ${e}</div>`).join("") + '</div>',
                confirmButtonColor: '#d33',
                width: '600px'
            });
            return false;
        }

        return true;
    }

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
@endsection