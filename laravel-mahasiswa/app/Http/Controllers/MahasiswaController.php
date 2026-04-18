<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    /**
     * Menampilkan halaman utama
     */
    public function index()
    {
        return view('mahasiswa.index');
    }

    /**
     * Membaca file JSON dan mengembalikan data mahasiswa
     */
    public function getData()
    {
        try {
            // Baca file JSON dari storage
            $jsonPath = storage_path('app/data/mahasiswa.json');

            if (!file_exists($jsonPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File data tidak ditemukan.'
                ], 404);
            }

            $jsonContent = file_get_contents($jsonPath);
            $mahasiswa   = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format data tidak valid.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'total'   => count($mahasiswa),
                'data'    => $mahasiswa
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}