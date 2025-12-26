<?php

namespace App\Http\Controllers;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman form lapor
     */
    public function create()
    {
        return view('lapor');
    }

    /**
     * Menampilkan halaman riwayat penanganan
     */
    public function riwayat()
    {
        return view('riwayat');
    }

    /**
     * Menampilkan halaman tindak lanjut
     */
    public function tindakLanjut($id)
    {
        return view('tindak-lanjut', compact('id'));
    }
}

