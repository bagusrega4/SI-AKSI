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

        <form action="{{ isset($form) ? route('form.update', $form->id) : route('form.store') }}" method="POST">
            @csrf
            @if(isset($form))
                @method('PUT')
            @endif
            <!-- Input judul form -->
            <div class="mb-4">
                <label for="form_title" class="form-label fw-bold">Judul Form</label>
                <input type="text" id="form_title" name="form_title" class="form-control"
                       placeholder="Masukkan judul form" 
                       value="{{ old('form_title', $form->title ?? '') }}" required>
            </div>

            <!-- Wrapper semua section -->
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
                                <!-- hidden id (opsional) -->
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
                                            </select>

                                            <div class="options-wrapper" style="{{ $question->type == 'multiple' ? 'display:block' : 'display:none' }}">
                                                <label>Jumlah Pilihan</label>
                                                <input type="number"
                                                    name="sections[{{ $sKey }}][questions][{{ $qKey }}][option_count]"
                                                    min="2" max="10"
                                                    value="{{ $question->type == 'multiple' ? ($question->options->count() ?? 2) : 0 }}"
                                                    class="form-control mb-2"
                                                    onchange="generateOptions(this, '{{ $sKey }}', '{{ $qKey }}')"
                                                    {{ $question->type == 'multiple' ? '' : 'disabled' }}>

                                                <div class="options-container">
                                                    @if($question->type == 'multiple')
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

            <!-- Tombol tambah section -->
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
        </select>
        <div class="options-wrapper" style="display:none;">
            <label>Jumlah Pilihan</label>
            <input type="number" name="sections[${sectionKey}][questions][${qKey}][option_count]" min="2" max="10" value="4" class="form-control mb-2" onchange="generateOptions(this, '${sectionKey}', '${qKey}')">
            <div class="options-container"></div>
        </div>
        <button type="button" class="btn btn-sm btn-danger mt-2" onclick="this.parentElement.remove()">Hapus Pertanyaan</button>`;
        const addBtn = section.querySelector(".btn-add-question");
        section.insertBefore(question, addBtn);
    }

    function toggleQuestionType(select, sectionKey, questionKey) {
        const wrapper = select.closest(".question-item").querySelector(".options-wrapper");
        const numberInput = wrapper.querySelector("input[type='number']");
        if (select.value === "multiple") {
            wrapper.style.display = "block";
            numberInput.disabled = false;
            if (parseInt(numberInput.value) < 2) numberInput.value = 2;
            generateOptions(numberInput, sectionKey, questionKey);
        } else {
            wrapper.style.display = "none";
            numberInput.disabled = true;  // <--- biar tidak tervalidasi saat submit
            numberInput.value = 0;        // <--- default ke 0
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
    console.log("Form submit dicek...");
    const questions = document.querySelectorAll(".question-item");
    console.log("Jumlah pertanyaan:", questions.length);

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
