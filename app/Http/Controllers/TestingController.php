<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class TestingController extends Controller
{
    //
    public function getData(Request $req)
    {
        $dataAll = Test::all(); // Ambil semua data (SELECT * FROM test // DB::table('tests')->get())

        // dd($dataAll); // <-- HAPUS ATAU KOMENTAR BARIS INI

        return view('test', [
            'data' => $dataAll,
            'name' => $req->name ? $req->name : "Dummy"
        ]);
    }
}
