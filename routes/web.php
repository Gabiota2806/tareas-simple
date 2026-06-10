<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\CareerController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::get('/subjects', function(){
    return view('subjects.index');
});

Route::middleware('auth')->group(function () {
    Route::post('/universities', [UniversityController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/universities', [UniversityController::class, 'store']);
    Route::post('/careers', [CareerController::class, 'store']);
});
