<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        return view('mahasiswa.index');
    }
    public function scanner()
    {
        return view('mahasiswa.scanner');
    }
}
