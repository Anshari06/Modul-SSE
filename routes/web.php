<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\LoginController;

Route::get('/', fn() => redirect()->route('guest'));

Route::get('/guest', [AntrianController::class, 'guest'])->name('guest');
Route::post('/guest', [AntrianController::class, 'store'])->name('antrian.store');

Route::get('/display', fn() => view('display.index'))->name('display');
Route::get('/sse/antrian', [AntrianController::class, 'stream'])->name('antrian.stream');

// Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin (protected)
Route::get('/admin', [AntrianController::class, 'admin'])->name('admin.dashboard')->middleware('auth');
Route::post('/call/{id}', [AntrianController::class, 'call'])->name('antrian.call')->middleware('auth');
Route::post('/done/{id}', [AntrianController::class, 'done'])->name('antrian.done')->middleware('auth');
Route::post('/reset', [AntrianController::class, 'reset'])->name('antrian.reset')->middleware('auth');
