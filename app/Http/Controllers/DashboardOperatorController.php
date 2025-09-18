<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardOperatorController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboardOperator');
    }
}
