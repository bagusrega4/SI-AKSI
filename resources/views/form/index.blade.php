@extends('layouts/app')

@section('stylecss')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">{{ isset($form) ? 'Edit Form Pertanyaan' : 'Buat Form Pertanyaan' }}</h3>
                <h6 class="op-7 mb-2">Halaman untuk membuat atau mengedit form pertanyaan.</h6>
            </div>
        </div>

        <form action="{{ isset($form) ? route('form.update', $form->id) : route('form.store') }}"
            method="POST"
            onsubmit="return validateForm(event)">
            @csrf
            @if(isset($form))
            @method('PUT')
            @endif
            
            <div class="mb-4">
                <label for="form_title" class="form-label fw-bold">Judul Form</label>
                <input type="text" id="form_title" name="form_title" class="form-control"
                    placeholder="Masukkan judul form"
                    value="{{ old('form_title', $form->title ?? '') }}" required>
            </div>

            <div id="sections-wrapper">
                @if(isset($form) && $form->sections)
                @foreach($form->sections as $section)
                @php $sKey = $section->id; @endphp

                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>Section {{ $loop->iteration }}</strong>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeSection('section-{{ $sKey }}')">Hapus Section</button>
                    </div>

                    <div class="card-body" id="section-{{ $sKey }}">
                        <input type="hidden" name="sections[{{ $sKey }}][id]" value="{{ $sKey }}">

                        <div class="mb-3">
                            <label>Judul Section</label>
                            <input type="text" name="sections[{{ $sKey }}][title]" class="form-control" value="{{ $section->title }}">
                        </div>

                        <div class="mb-3">
                            <label>Deskripsi Section</label>
                            <textarea name="sections[{{ $sKey }}][description]" class="form-control" rows="2">{{ old("sections.$sKey.description", $section->description ?? '') }}</textarea>
                        </div>

                        <div class="questions-wrapper mt-3">
                            @foreach($section->questions as $question)
                            @php $qKey = $question->id; @endphp
                            <div class="question-item mb-3 p-3 border rounded">
                                <input type="hidden" name="sections[{{ $sKey }}][questions][{{ $qKey }}][id]" value="{{ $qKey }}">

                                <label>Pertanyaan {{ $loop->iteration }}</label>
                                <input type="text"
                                    name="sections[{{ $sKey }}][questions][{{ $qKey }}][text]"
                                    class="form-control mb-2"
                                    value="{{ $question->text }}">

                                <label>Tipe Pertanyaan</label>
                                <select class="form-select mb-2"
                                    name="sections[{{ $sKey }}][questions][{{ $qKey }}][type]"
                                    onchange="toggleQuestionType(this, '{{ $sKey }}', '{{ $qKey }}')">
                                    <option value="text" {{ $question->type == 'text' ? 'selected' : '' }}>Isian (Text)</option>
                                    <option value="multiple" {{ $question->type == 'multiple' ? 'selected' : '' }}>Pilihan Ganda</option>
                                    <option value="checkbox" {{ $question->type == 'checkbox' ? 'selected' : '' }}>Checkbox (Pilih Banyak)</option>
                                </select>

                                <!-- Min & Max Selections (hanya untuk checkbox) -->
                                <div class="selection-limits-wrapper mb-2" style="{{ $question->type == 'checkbox' ? 'display:block' : 'display:none' }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Minimal Pilihan <small class="text-muted">(opsional)</small></label>
                                            <input type="number"
                                                name="sections[{{ $sKey }}][questions][{{ $qKey }}][min_selections]"
                                                class="form-control min-selections-input"
                                                min="1"
                                                placeholder="Misal: 1"
                                                value="{{ $question->min_selections ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Maksimal Pilihan <small class="text-muted">(opsional)</small></label>
                                            <input type="number"
                                                name="sections[{{ $sKey }}][questions][{{ $qKey }}][max_selections]"
                                                class="form-control max-selections-input"
                                                min="1"
                                                placeholder="Misal: 3"
                                                value="{{ $question->max_selections ?? '' }}">
                                        </div>
                                    </div>
                                    <small class="text-info d-block mt-1">
                                        <i class="fas fa-info-circle"></i> Kosongkan jika tidak ada batasan. Min tidak boleh > Max.
                                    </small>
                                </div>

                                <div class="options-wrapper" style="{{ in_array($question->type, ['multiple', 'checkbox']) ? 'display:block' : 'display:none' }}">
                                    <label>Jumlah Pilihan</label>
                                    <input type="number"
                                        name="sections[{{ $sKey }}][questions][{{ $qKey }}][option_count]"
                                        min="2"
                                        value="{{ in_array($question->type, ['multiple', 'checkbox']) ? ($question->options->count() ?? 2) : 0 }}"
                                        class="form-control mb-2 option-count-input"
                                        onchange="generateOptions(this, '{{ $sKey }}', '{{ $qKey }}')"
                                        {{ in_array($question->type, ['multiple', 'checkbox']) ? '' : 'disabled' }}>

                                    <div class="options-container">
                                        @if(in_array($question->type, ['multiple', 'checkbox']))
                                        @foreach($question->options as $opt)
                                        <input type="text"
                                            class="form-control mb-1"
                                            name="sections[{{ $sKey }}][questions][{{ $qKey }}][options][{{ $opt->id }}]"
                                            value="{{ $opt->option_text }}">
                                        @endforeach
                                        @endif
                                    </div>
                                </div>

                                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="this.parentElement.remove()">Hapus Pertanyaan</button>
                            </div>
                            @endforeach

                            <button type="button" class="btn btn-outline-primary btn-sm btn-add-question" onclick="addQuestion('section-{{ $sKey }}','{{ $sKey }}')">+ Tambah Pertanyaan</button>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>

            <div class="mb-3">
                <button type="button" class="btn btn-primary" id="btnAddSection" onclick="addSection()">+ Tambah Section</button>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">{{ isset($form) ? 'Update Form' : 'Simpan Form' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    let newSectionCounter = 0;
    let newQuestionCounter = 0;
    let newOptionCounter = 0;

    function addSection() {
        const sKey = 'new-' + (++newSectionCounter);
        const sectionId = `section-${sKey}`;
        const wrapper = document.getElementById("sections-wrapper");

        const section = document.createElement("div");
        section.classList.add("card", "mb-4");
        section.innerHTML = `
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Section (baru)</strong>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeSection('${sectionId}')">Hapus Section</button>
        </div>
        <div class="card-body" id="${sectionId}">
            <input type="hidden" name="sections[${sKey}][id]" value="">
            <div class="mb-3">
                <label>Judul Section</label>
                <input type="text" name="sections[${sKey}][title]" class="form-control" placeholder="Masukkan judul section">
            </div>
            <div class="mb-3">
                <label>Deskripsi Section</label>
                <textarea name="sections[${sKey}][description]" class="form-control" rows="2" placeholder="Tuliskan deskripsi section"></textarea>
            </div>
            <div class="questions-wrapper mt-3">
                <button type="button" class="btn btn-outline-primary btn-sm btn-add-question" onclick="addQuestion('${sectionId}','${sKey}')">+ Tambah Pertanyaan</button>
            </div>
        </div>`;
        wrapper.appendChild(section);
    }

    function addQuestion(sectionDomId, sectionKey) {
        const section = document.getElementById(sectionDomId).querySelector(".questions-wrapper");
        const qKey = 'new-' + (++newQuestionCounter);
        const questionCount = section.querySelectorAll(".question-item").length + 1;

        const question = document.createElement("div");
        question.classList.add("question-item", "mb-3", "p-3", "border", "rounded");
        question.innerHTML = `
        <input type="hidden" name="sections[${sectionKey}][questions][${qKey}][id]" value="">
        <label>Pertanyaan ${questionCount}</label>
        <input type="text" name="sections[${sectionKey}][questions][${qKey}][text]" class="form-control mb-2" placeholder="Tulis pertanyaan">
        <label>Tipe Pertanyaan</label>
        <select class="form-select mb-2" name="sections[${sectionKey}][questions][${qKey}][type]" onchange="toggleQuestionType(this, '${sectionKey}', '${qKey}')">
            <option value="text">Isian (Text)</option>
            <option value="multiple">Pilihan Ganda</option>
            <option value="checkbox">Checkbox (Pilih Banyak)</option>
        </select>
        
        <div class="selection-limits-wrapper mb-2" style="display:none;">
            <div class="row">
                <div class="col-md-6">
                    <label>Minimal Pilihan <small class="text-muted">(opsional)</small></label>
                    <input type="number" name="sections[${sectionKey}][questions][${qKey}][min_selections]" class="form-control min-selections-input" min="1" placeholder="Misal: 1">
                </div>
                <div class="col-md-6">
                    <label>Maksimal Pilihan <small class="text-muted">(opsional)</small></label>
                    <input type="number" name="sections[${sectionKey}][questions][${qKey}][max_selections]" class="form-control max-selections-input" min="1" placeholder="Misal: 3">
                </div>
            </div>
            <small class="text-info d-block mt-1">
                <i class="fas fa-info-circle"></i> Kosongkan jika tidak ada batasan. Min tidak boleh > Max.
            </small>
        </div>

        <div class="options-wrapper" style="display:none;">
            <label>Jumlah Pilihan</label>
            <input type="number" name="sections[${sectionKey}][questions][${qKey}][option_count]" min="2" value="4" class="form-control mb-2 option-count-input" onchange="generateOptions(this, '${sectionKey}', '${qKey}')">
            <div class="options-container"></div>
        </div>
        <button type="button" class="btn btn-sm btn-danger mt-2" onclick="this.parentElement.remove()">Hapus Pertanyaan</button>`;
        const addBtn = section.querySelector(".btn-add-question");
        section.insertBefore(question, addBtn);
    }

    function toggleQuestionType(select, sectionKey, questionKey) {
        const questionItem = select.closest(".question-item");
        const wrapper = questionItem.querySelector(".options-wrapper");
        const limitsWrapper = questionItem.querySelector(".selection-limits-wrapper");
        const numberInput = wrapper.querySelector(".option-count-input");
        const minInput = limitsWrapper.querySelector(".min-selections-input");
        const maxInput = limitsWrapper.querySelector(".max-selections-input");

        if (select.value === "checkbox") {
            // Tampilkan options + min/max limits
            wrapper.style.display = "block";
            limitsWrapper.style.display = "block";
            numberInput.disabled = false;
            if (parseInt(numberInput.value) < 2) numberInput.value = 2;
            generateOptions(numberInput, sectionKey, questionKey);
        } else if (select.value === "multiple") {
            // Tampilkan options, sembunyikan limits
            wrapper.style.display = "block";
            limitsWrapper.style.display = "none";
            numberInput.disabled = false;
            if (parseInt(numberInput.value) < 2) numberInput.value = 2;
            minInput.value = '';
            maxInput.value = '';
            generateOptions(numberInput, sectionKey, questionKey);
        } else {
            // Text: sembunyikan semua
            wrapper.style.display = "none";
            limitsWrapper.style.display = "none";
            numberInput.disabled = true;
            numberInput.value = 0;
            minInput.value = '';
            maxInput.value = '';
            wrapper.querySelector(".options-container").innerHTML = "";
        }
    }

    function generateOptions(input, sectionKey, questionKey) {
        const count = parseInt(input.value) || 0;
        const container = input.parentElement.querySelector(".options-container");
        container.innerHTML = "";
        for (let i = 1; i <= count; i++) {
            const optKey = 'new-' + (++newOptionCounter);
            container.innerHTML += `<input type="text" class="form-control mb-1" name="sections[${sectionKey}][questions][${questionKey}][options][${optKey}]" placeholder="Pilihan ${i}">`;
        }
    }

    function removeSection(sectionId) {
        const el = document.getElementById(sectionId);
        if (el) el.parentElement.remove();
    }

    function validateForm(event) {
        const questions = document.querySelectorAll(".question-item");

        if (questions.length === 0) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Form tidak valid!',
                text: 'Minimal harus ada 1 pertanyaan sebelum menyimpan form.',
                confirmButtonColor: '#d33'
            });
            return false;
        }

        let emptyOptions = [];
        let invalidLimits = [];

        questions.forEach((question, idx) => {
            const typeSelect = question.querySelector('select[name*="[type]"]');
            const questionType = typeSelect ? typeSelect.value : 'text';

            if (questionType === 'multiple' || questionType === 'checkbox') {
                const optionInputs = question.querySelectorAll('.options-container input[type="text"]');
                optionInputs.forEach(input => {
                    if (input.value.trim() === '') {
                        emptyOptions.push(`Pertanyaan ${idx + 1} - ada pilihan yang kosong`);
                    }
                });

                // Validasi min/max untuk checkbox
                if (questionType === 'checkbox') {
                    const minInput = question.querySelector('input[name*="[min_selections]"]');
                    const maxInput = question.querySelector('input[name*="[max_selections]"]');
                    const optionsCount = optionInputs.length;
                    
                    const minVal = minInput && minInput.value.trim() !== '' ? parseInt(minInput.value) : null;
                    const maxVal = maxInput && maxInput.value.trim() !== '' ? parseInt(maxInput.value) : null;

                    // Cek min tidak boleh lebih besar dari max
                    if (minVal !== null && maxVal !== null && minVal > maxVal) {
                        invalidLimits.push(`Pertanyaan ${idx + 1} - Minimal pilihan (${minVal}) tidak boleh lebih besar dari maksimal (${maxVal})`);
                    }

                    // Cek min tidak boleh lebih besar dari jumlah opsi
                    if (minVal !== null && minVal > optionsCount) {
                        invalidLimits.push(`Pertanyaan ${idx + 1} - Minimal pilihan (${minVal}) tidak boleh lebih besar dari jumlah opsi (${optionsCount})`);
                    }

                    // Cek max tidak boleh lebih besar dari jumlah opsi
                    if (maxVal !== null && maxVal > optionsCount) {
                        invalidLimits.push(`Pertanyaan ${idx + 1} - Maksimal pilihan (${maxVal}) tidak boleh lebih besar dari jumlah opsi (${optionsCount})`);
                    }

                    // Cek min minimal 1 jika diisi
                    if (minVal !== null && minVal < 1) {
                        invalidLimits.push(`Pertanyaan ${idx + 1} - Minimal pilihan tidak boleh kurang dari 1`);
                    }

                    // Cek max minimal 1 jika diisi
                    if (maxVal !== null && maxVal < 1) {
                        invalidLimits.push(`Pertanyaan ${idx + 1} - Maksimal pilihan tidak boleh kurang dari 1`);
                    }
                }
            }
        });

        if (emptyOptions.length > 0) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Options tidak boleh kosong!',
                html: emptyOptions.join('<br>'),
                confirmButtonColor: '#d33'
            });
            return false;
        }

        if (invalidLimits.length > 0) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validasi Min/Max Pilihan Gagal!',
                html: invalidLimits.join('<br>'),
                confirmButtonColor: '#d33'
            });
            return false;
        }

        return true;
    }

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        confirmButtonColor: '#198754'
    })
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session("error") }}',
        confirmButtonColor: '#d33'
    })
    @endif
</script>
@endsection