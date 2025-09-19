<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->id_role != 3) {
            abort(403, 'Unauthorized');
        }

        return view('dashboard');
    }
}
