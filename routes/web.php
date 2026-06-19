<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CalendarController;
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

    // CRUD de Asignaturas (Subjects)
    Route::get('/subjects', [SubjectController::class, 'index']);
    Route::post('/subjects', [SubjectController::class, 'store']);
    Route::patch('/subjects/{subject}', [SubjectController::class, 'update']);
    Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy']);

    Route::get('/tasks/create', function () {
        return view('tasks.create');
    })->name('tasks.create');

    // API Dinámica - Tareas (CRUD Core y Kanban)
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::patch('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    // API Dinámica - Calendario
    Route::get('/calendar/events', [CalendarController::class, 'events']);

    // Endpoints Backend (Universidades y Carreras)
    Route::get('/universities', [UniversityController::class, 'index']);
    Route::post('/universities', [UniversityController::class, 'store']);

    Route::get('/careers', [CareerController::class, 'index']);
    Route::post('/careers', [CareerController::class, 'store']);

    //vista de prueba
    Route::get('/tasks-demo', function () {
        return view('tasks.index');
    })->middleware('auth');
});
