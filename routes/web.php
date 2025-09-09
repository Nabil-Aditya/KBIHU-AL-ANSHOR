<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
});



Route::get('/admin/index', function () {
    return view('/admin/index');
});

Route::get('/pengguna/index', function () {
    return view('/pengguna/index');
});