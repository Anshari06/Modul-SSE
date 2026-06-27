<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('guest.index');
});

Route::get('/admin', function () {
    return view('admin.index');
})->name('admin.dashboard');

Route::get('/display', function () {
    return view('display.index');
})->name('display');
