<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreateFormController extends Controller
{
    public function index(Request $request)
    {
        return view('form.create');
    }
}
