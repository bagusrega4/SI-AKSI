<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\FormAnswer;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AnswerExport;
use Barryvdh\DomPDF\Facade\Pdf;

class JawabanController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->id_role != 3) {
            abort(403, 'Unauthorized');
        }

        $forms = Form::orderBy('created_at', 'desc')->get();

        $answers = [];
        if ($request->form_id) {
            $answers = FormAnswer::with(['user.pegawai', 'details.question.section'])
                ->where('form_id', $request->form_id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('jawaban.index', compact('forms', 'answers'));
    }


    public function destroy($id)
    {
        $jawaban = FormAnswer::findOrFail($id);
        $formId = $jawaban->form_id;

        $jawaban->delete();

        return redirect()->route('jawaban.index', ['form_id' => $formId])
            ->with('success', 'Jawaban berhasil dihapus.');
    }

    public function exportExcel($form_id)
    {
        return Excel::download(new AnswerExport($form_id), 'jawaban_form_' . $form_id . '.xlsx');
    }
}
