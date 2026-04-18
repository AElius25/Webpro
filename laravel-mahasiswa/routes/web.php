<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama
Route::get('/', [MahasiswaController::class, 'index'])->name('mahasiswa.index');

// API route untuk mengambil data mahasiswa via AJAX
Route::get('/api/mahasiswa', [MahasiswaController::class, 'getData'])->name('mahasiswa.data');