<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\Section;
use App\Models\Question;
use App\Models\Option;
use App\Models\FormAnswer;
use App\Models\AnswerDetail;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::with('sections.questions.options')->get();
        return view('form.index', compact('forms'));
    }

    public function create()
    {
        return view('form.create'); // halaman form builder
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // 1. Simpan form utama
            $form = Form::create([
                'title' => $request->input('form_title', 'Form Baru'),
            ]);

            // 2. Loop sections
            if ($request->has('sections')) {
                foreach ($request->sections as $secIndex => $secData) {
                    $section = Section::create([
                        'form_id'     => $form->id,
                        'title'       => $secData['title'] ?? "Section $secIndex",
                        'description' => $secData['description'] ?? null,
                    ]);

                    // 3. Loop questions di tiap section
                    if (isset($secData['questions'])) {
                        foreach ($secData['questions'] as $qIndex => $qData) {
                            $question = Question::create([
                                'section_id' => $section->id,
                                'text'       => $qData['text'] ?? "Pertanyaan $qIndex",
                                'type'       => $qData['type'] ?? 'text',
                            ]);

                            // 4. Jika multiple → simpan options
                            if ($question->type === 'multiple' && isset($qData['options'])) {
                                foreach ($qData['options'] as $opt) {
                                    if (!empty($opt)) {
                                        Option::create([
                                            'question_id' => $question->id,
                                            'option_text' => $opt,
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('form.show', $form->id)
                ->with('success', 'Form berhasil dibuat, silakan isi!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $form = Form::with('sections.questions.options')->findOrFail($id);
        return view('form.show', compact('form'));
    }

    public function list()
    {
        $forms = Form::all();
        return view('form.list', compact('forms'));
    }

    public function storeAnswer(Request $request, $id)
    {
        $form = Form::findOrFail($id);

        // Simpan jawaban utama
        $formAnswer = FormAnswer::create([
            'form_id' => $form->id,
            'user_id' => auth()->id() ?? null,
        ]);

        // Loop semua jawaban
        foreach ($request->input('answers') as $questionId => $answerText) {
            $question = Question::find($questionId);

            AnswerDetail::create([
                'form_answer_id' => $formAnswer->id,
                'section_id'     => $question->section_id, // ambil dari relasi
                'question_id'    => $questionId,
                'answer_text'    => $answerText,
            ]);
        }

        return redirect()->route('form.list')->with('success', 'Jawaban berhasil disimpan!');
    }

    public function edit(Form $form)
    {
        $form->load('sections.questions.options');
        return view('form.index', compact('form'));
    }

    public function update(Request $request, Form $form)
    {
        DB::beginTransaction();
        try {
            // update form title
            $form->update([
                'title' => $request->input('form_title', $form->title),
            ]);

            $incomingSections = $request->input('sections', []);
            $keptSectionIds = [];

            foreach ($incomingSections as $sKey => $sData) {
                // create new section
                if (strpos($sKey, 'new-') === 0) {
                    $section = Section::create([
                        'form_id'     => $form->id,
                        'title'       => $sData['title'] ?? null,
                        'description' => $sData['description'] ?? null,
                    ]);
                } else {
                    $secId = (int)$sKey;
                    $section = Section::where('form_id', $form->id)->find($secId);
                    if ($section) {
                        $section->update([
                            'title'       => $sData['title'] ?? $section->title,
                            'description' => $sData['description'] ?? $section->description,
                        ]);
                    } else {
                        // fallback: create if not found
                        $section = Section::create([
                            'form_id'     => $form->id,
                            'title'       => $sData['title'] ?? null,
                            'description' => $sData['description'] ?? null,
                        ]);
                    }
                }

                $keptSectionIds[] = $section->id;

                // QUESTIONS
                $incomingQuestions = $sData['questions'] ?? [];
                $keptQuestionIds = [];

                foreach ($incomingQuestions as $qKey => $qData) {
                    if (strpos($qKey, 'new-') === 0) {
                        $question = Question::create([
                            'section_id' => $section->id,
                            'text'       => $qData['text'] ?? null,
                            'type'       => $qData['type'] ?? 'text',
                        ]);
                    } else {
                        $qid = (int)$qKey;
                        $question = Question::find($qid);
                        if ($question && $question->section_id == $section->id) {
                            $question->update([
                                'text' => $qData['text'] ?? $question->text,
                                'type' => $qData['type'] ?? $question->type,
                            ]);
                        } else {
                            // fallback create
                            $question = Question::create([
                                'section_id' => $section->id,
                                'text'       => $qData['text'] ?? null,
                                'type'       => $qData['type'] ?? 'text',
                            ]);
                        }
                    }

                    $keptQuestionIds[] = $question->id;

                    // OPTIONS
                    $incomingOptions = $qData['options'] ?? [];
                    $keptOptionIds = [];

                    foreach ($incomingOptions as $optKey => $optVal) {
                        if (strpos($optKey, 'new-') === 0) {
                            $opt = Option::create([
                                'question_id' => $question->id,
                                'option_text' => $optVal,
                            ]);
                        } else {
                            $optId = (int)$optKey;
                            $opt = Option::find($optId);
                            if ($opt && $opt->question_id == $question->id) {
                                $opt->update(['option_text' => $optVal]);
                            } else {
                                $opt = Option::create([
                                    'question_id' => $question->id,
                                    'option_text' => $optVal,
                                ]);
                            }
                        }
                        $keptOptionIds[] = $opt->id;
                    }

                    // delete options removed by user
                    Option::where('question_id', $question->id)
                        ->whereNotIn('id', $keptOptionIds ?: [0])
                        ->delete();
                }

                // delete questions removed by user
                Question::where('section_id', $section->id)
                    ->whereNotIn('id', $keptQuestionIds ?: [0])
                    ->delete();
            }

            // delete sections removed by user
            Section::where('form_id', $form->id)
                ->whereNotIn('id', $keptSectionIds ?: [0])
                ->delete();

            DB::commit();

            return redirect()->route('form.list')->with('success', 'Form berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy(Form $form)
    {
        $form->delete();

        return redirect()->route('form.list')->with('success', 'Form berhasil dihapus.');
    }
}
