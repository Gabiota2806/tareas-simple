<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\CareerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubjectController;

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
 
    // CRUD de Asignaturas (Subjects) con controladores y vistas Blade
    Route::resource('subjects', SubjectController::class);
   
    // Vistas Web (Frontend Blade)
    Route::get('/subjects', function () {
        return view('subjects.index');
    }); // Ruta para mostrar el formulario de creación de asignatura

    // Endpoints Backend (Universidades y Carreras)
    Route::get('/universities', [UniversityController::class, 'index']);
    Route::post('/universities', [UniversityController::class, 'store']);

    Route::get('/careers', [CareerController::class, 'index']);
    Route::post('/careers', [CareerController::class, 'store']);

}); require __DIR__.'/auth.php';    // Rutas de autenticación de Breeze (login, register, etc.)

   