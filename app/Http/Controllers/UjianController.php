<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class UjianController extends Controller
{
    public function index()
    {
        $data_soal = DB::table('soal')->get();

        return view('ujian', ['data_soal' => $data_soal]);
    }
}
