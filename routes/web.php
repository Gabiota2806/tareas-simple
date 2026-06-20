<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

// 1. Rutas Públicas
Route::get('/', function () {
    return view('welcome');
});

// 2. Rutas Protegidas de Breeze (Autenticación nativa)
Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    $activeUniId = session('active_university_id');
    
    $query = \App\Models\Task::where('user_id', \Illuminate\Support\Facades\Auth::id())
        ->active()
        ->with('subject')
        ->byPriority();

    if ($activeUniId) {
        $query->whereHas('subject.career', function($q) use ($activeUniId) {
            $q->where('university_id', $activeUniId);
        });
    }

    if ($request->filled('career_id')) {
        $query->whereHas('subject', function($q) use ($request) {
            $q->where('career_id', $request->career_id);
        });
    }

    $tasks = $query->get();
        
    return view('dashboard', compact('tasks'));
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';

// 3. Rutas Protegidas del Sistema UniTask
Route::middleware(['auth', 'verified'])->group(function () {

    // Cambiar universidad activa
    Route::post('/active-university', function (\Illuminate\Http\Request $request) {
        $request->validate(['university_id' => 'required|exists:universities,id']);
        session(['active_university_id' => $request->university_id]);
        return back();
    })->name('active-university.set');

    // Buscador global
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    // Perfil de Usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD de Asignaturas (Subjects)
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
    Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
    Route::get('/subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');
    Route::get('/subjects/{subject}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
    Route::patch('/subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');

    // API Dinámica - Tareas (CRUD Core y Kanban)
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    // API Dinámica - Calendario
    Route::get('/calendar/events', [CalendarController::class, 'events']);

    // Endpoints Backend (Universidades y Carreras)
    Route::get('/universities', [UniversityController::class, 'index'])->name('universities.index');
    Route::get('/universities/create', [UniversityController::class, 'create'])->name('universities.create');
    Route::post('/universities', [UniversityController::class, 'store'])->name('universities.store');
    Route::get('/universities/{university}/edit', [UniversityController::class, 'edit'])->name('universities.edit');
    Route::patch('/universities/{university}', [UniversityController::class, 'update'])->name('universities.update');
    Route::delete('/universities/{university}', [UniversityController::class, 'destroy'])->name('universities.destroy');

    Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
    Route::get('/careers/create', [CareerController::class, 'create'])->name('careers.create');
    Route::post('/careers', [CareerController::class, 'store'])->name('careers.store');
    Route::get('/careers/{career}/edit', [CareerController::class, 'edit'])->name('careers.edit');
    Route::patch('/careers/{career}', [CareerController::class, 'update'])->name('careers.update');
    Route::delete('/careers/{career}', [CareerController::class, 'destroy'])->name('careers.destroy');

    //vista de prueba
    Route::get('/tasks-demo', function () {
        return view('tasks.index');
    })->middleware('auth');
});
