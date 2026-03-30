<?php

use Illuminate\Support\Facades\Route;

// Route default
Route::get('/', function () {
    return view('welcome');
});

// Exercise 1: akses http://localhost:8000/lat1
Route::get('/lat1', 'App\Http\Controllers\Lat1Controller@index');

// Exercise 2: akses http://localhost:8000/lat1/m2
Route::get('/lat1/m2', 'App\Http\Controllers\Lat1Controller@method2');
