<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\CareerController;
use Illuminate\Support\Facades\Route;

// 1. Rutas Públicas
Route::get('/', function () {
    return view('welcome');
});

// 2. Rutas Protegidas de Breeze (Autenticación nativa)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';

// 3. Rutas Protegidas del Sistema UniTask
Route::middleware('auth')->group(function () {

    // Perfil de Usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Vistas Web (Frontend Blade)
    Route::get('/subjects', function () {
        return view('subjects.index');
    });

    // Endpoints Backend (Universidades y Carreras)
    Route::get('/universities', [UniversityController::class, 'index']);
    Route::post('/universities', [UniversityController::class, 'store']);

    Route::get('/careers', [CareerController::class, 'index']);
    Route::post('/careers', [CareerController::class, 'store']);
});
