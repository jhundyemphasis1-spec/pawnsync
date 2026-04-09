<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicScrapboardRecordController;
use App\Http\Controllers\ScrapboardRecordController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicScrapboardRecordController::class, 'index'])->name('public.index');
Route::get('/login', fn () => redirect()->route('public.index'))->name('login');
Route::post('/login', [AuthController::class, 'store'])->middleware('guest')->name('login.attempt');
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/scrapboard-records')->name('dashboard');
    Route::resource('scrapboard-records', ScrapboardRecordController::class)->except(['show', 'create']);
});
