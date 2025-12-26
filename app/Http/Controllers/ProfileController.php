<?php

namespace App\Http\Controllers;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profile sekolah
     */
    public function index()
    {
        return view('profile');
    }
}

