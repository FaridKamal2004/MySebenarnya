<?php

use App\Http\Controllers\AgencyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/agency', [AgencyController::class,'index'])->name('agencies.index');
Route::get('/agency/create', [AgencyController::class,'create'])->name('agencies.create');
Route::post('/agency', [AgencyController::class,'store'])->name('agencies.store');