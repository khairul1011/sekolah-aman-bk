<?php

namespace App\Http\Controllers;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Menampilkan halaman register
     */
    public function showRegister()
    {
        return view('register');
    }
}

