<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Lat1Controller extends Controller
{
    // Exercise 1: menampilkan nama dan asal
    public function index()
    {
        $data["nama"] = "Agus";
        $data["asal"] = "Bandung";
        return view('v_latihan1', $data);
    }

    // Exercise 2: menampilkan daftar mahasiswa
    public function method2()
    {
        $data['title'] = 'Daftar Mahasiswa';
        $data['daf_mhs'] = array(
            array("nama" => "Agus",  "asal" => "Bandung"),
            array("nama" => "Budi",  "asal" => "Jakarta"),
            array("nama" => "Roni",  "asal" => "Surabaya")
        );
        return view('v_latihan2', $data);
    }
}
