<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard
     */
    public function index()
    {
        return view('dashboard');
    }
}

