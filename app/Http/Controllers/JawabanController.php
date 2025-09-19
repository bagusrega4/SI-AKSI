<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\FormAnswer;

class JawabanController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->id_role != 3) {
            abort(403, 'Unauthorized');
        }

        $forms = Form::all();

        $answers = [];
        if ($request->form_id) {
            $answers = FormAnswer::with(['user', 'details.question'])
                        ->where('form_id', $request->form_id)
                        ->get();
        }

        return view('jawaban.index', compact('forms', 'answers'));
    }
}
