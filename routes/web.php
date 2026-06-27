<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AntrianController;

Route::get('/', fn() => redirect()->route('guest'));

Route::get('/guest', [AntrianController::class, 'guest'])->name('guest');
Route::post('/guest', [AntrianController::class, 'store'])->name('antrian.store');

Route::get('/admin', [AntrianController::class, 'admin'])->name('admin.dashboard');
Route::post('/call/{id}', [AntrianController::class, 'call'])->name('antrian.call');
Route::post('/done/{id}', [AntrianController::class, 'done'])->name('antrian.done');
Route::post('/reset', [AntrianController::class, 'reset'])->name('antrian.reset');

Route::get('/display', function () {
    return view('display.index');
})->name('display');

Route::get('/sse/antrian', [AntrianController::class, 'stream'])->name('antrian.stream');
