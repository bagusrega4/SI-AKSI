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
        return view('form.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $form = Form::create([
                'title' => $request->input('form_title', 'Form Baru'),
            ]);

            if ($request->has('sections')) {
                foreach ($request->sections as $secIndex => $secData) {
                    $section = Section::create([
                        'form_id'     => $form->id,
                        'title'       => $secData['title'] ?? "Section $secIndex",
                        'description' => $secData['description'] ?? null,
                    ]);

                    if (isset($secData['questions'])) {
                        foreach ($secData['questions'] as $qIndex => $qData) {
                            $minSelections = null;
                            $maxSelections = null;

                            if ($qData['type'] === 'checkbox') {
                                $minSelections = isset($qData['min_selections']) && $qData['min_selections'] !== '' 
                                    ? (int)$qData['min_selections'] 
                                    : null;
                                $maxSelections = isset($qData['max_selections']) && $qData['max_selections'] !== '' 
                                    ? (int)$qData['max_selections'] 
                                    : null;
                            }

                            $question = Question::create([
                                'section_id' => $section->id,
                                'text'       => $qData['text'] ?? "Pertanyaan $qIndex",
                                'type'       => $qData['type'] ?? 'text',
                                'min_selections' => $minSelections,
                                'max_selections' => $maxSelections,
                            ]);

                            if (in_array($question->type, ['multiple', 'checkbox']) && isset($qData['options'])) {
                                foreach ($qData['options'] as $optKey => $opt) {
                                    if (!empty(trim($opt))) {
                                        Option::create([
                                            'question_id' => $question->id,
                                            'option_text' => trim($opt),
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
        $userId = auth()->id();

        $forms = Form::with('answers')
            ->select('forms.*')
            ->selectRaw('CASE WHEN form_answers.id IS NULL THEN 0 ELSE 1 END AS sudah_isi')
            ->leftJoin('form_answers', function ($join) use ($userId) {
                $join->on('forms.id', '=', 'form_answers.form_id')
                    ->where('form_answers.user_id', '=', $userId);
            })
            ->orderBy('sudah_isi', 'ASC')
            ->orderBy('forms.created_at', 'DESC')
            ->get();

        return view('form.list', compact('forms'));
    }

    public function storeAnswer(Request $request, $id)
    {
        $form = Form::findOrFail($id);

        // Validasi checkbox min/max selections
        foreach ($request->input('answers', []) as $questionId => $answerValue) {
            $question = Question::find($questionId);
            
            if ($question && $question->type === 'checkbox') {
                $selectedCount = is_array($answerValue) ? count($answerValue) : 0;

                // Validasi minimal
                if ($question->min_selections && $selectedCount < $question->min_selections) {
                    return back()->with('error', 
                        "Pertanyaan '{$question->text}' harus memilih minimal {$question->min_selections} pilihan!"
                    )->withInput();
                }

                // Validasi maksimal
                if ($question->max_selections && $selectedCount > $question->max_selections) {
                    return back()->with('error', 
                        "Pertanyaan '{$question->text}' hanya boleh memilih maksimal {$question->max_selections} pilihan!"
                    )->withInput();
                }
            }
        }

        // Simpan jawaban utama
        $formAnswer = FormAnswer::create([
            'form_id' => $form->id,
            'user_id' => auth()->id() ?? null,
        ]);

        // Loop semua jawaban
        foreach ($request->input('answers') as $questionId => $answerText) {
            $question = Question::find($questionId);

            // Jika checkbox (array), gabungkan dengan koma
            if (is_array($answerText)) {
                $answerText = implode(', ', $answerText);
            }

            AnswerDetail::create([
                'form_answer_id' => $formAnswer->id,
                'section_id'     => $question->section_id,
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
                    $questionType = $qData['type'] ?? 'text';
                    
                    $minSelections = null;
                    $maxSelections = null;

                    if ($questionType === 'checkbox') {
                        $minSelections = isset($qData['min_selections']) && $qData['min_selections'] !== '' 
                            ? (int)$qData['min_selections'] 
                            : null;
                        $maxSelections = isset($qData['max_selections']) && $qData['max_selections'] !== '' 
                            ? (int)$qData['max_selections'] 
                            : null;
                    }

                    if (strpos($qKey, 'new-') === 0) {
                        $question = Question::create([
                            'section_id' => $section->id,
                            'text'       => $qData['text'] ?? null,
                            'type'       => $questionType,
                            'min_selections' => $minSelections,
                            'max_selections' => $maxSelections,
                        ]);
                    } else {
                        $qid = (int)$qKey;
                        $question = Question::find($qid);
                        if ($question && $question->section_id == $section->id) {
                            $question->update([
                                'text' => $qData['text'] ?? $question->text,
                                'type' => $questionType,
                                'min_selections' => $minSelections,
                                'max_selections' => $maxSelections,
                            ]);
                        } else {
                            $question = Question::create([
                                'section_id' => $section->id,
                                'text'       => $qData['text'] ?? null,
                                'type'       => $questionType,
                                'min_selections' => $minSelections,
                                'max_selections' => $maxSelections,
                            ]);
                        }
                    }

                    $keptQuestionIds[] = $question->id;

                    // OPTIONS
                    $incomingOptions = $qData['options'] ?? [];
                    $keptOptionIds = [];

                    if (in_array($question->type, ['multiple', 'checkbox'])) {
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
                    }

                    Option::where('question_id', $question->id)
                        ->whereNotIn('id', $keptOptionIds ?: [0])
                        ->delete();
                }

                Question::where('section_id', $section->id)
                    ->whereNotIn('id', $keptQuestionIds ?: [0])
                    ->delete();
            }

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