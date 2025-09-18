<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardKetuaController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboardKetua');
    }
}
