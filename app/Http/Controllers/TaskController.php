<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Retorna todas las tareas activas del usuario, ordenadas por prioridad.
     */
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())
            ->active()
            ->with('subject')
            ->byPriority()
            ->get();
            
        return response()->json($tasks);
    }

    public function create(Request $request)
    {
        $activeUniId = session('active_university_id');
        $subjectsQuery = Subject::where('user_id', Auth::id())
            ->where('is_active', true);

        if ($activeUniId) {
            $subjectsQuery->whereHas('career', function($q) use ($activeUniId) {
                $q->where('university_id', $activeUniId);
            });
        }

        $subjects = $subjectsQuery->get();
        $defaultSubjectId = $request->query('subject_id', '');
            
        return view('tasks.create', compact('subjects', 'defaultSubjectId'));
    }

    /**
     * Crea una nueva tarea (CRUD base + actualización incremental)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'task_type' => 'required|in:parcial,final,tp,normal',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'task_time' => 'nullable|date_format:H:i',
            'estimated_time' => 'nullable|integer|min:1',
            'reminder' => 'boolean',
            'subject_id' => 'required|exists:subjects,id',
            'parent_id' => 'nullable|exists:tasks,id',
            'team_members' => 'nullable|string|max:255',
            'submission_format' => 'nullable|string|max:100',
            'grade' => 'nullable|numeric|between:0,10',
            'enrollment_date' => 'nullable|date',
            'exam_type' => 'nullable|string|max:50'
        ]);

        // Restricción: La materia debe ser del usuario y estar ACTIVA
        $subject = Subject::where('id', $validated['subject_id'])
            ->where('user_id', Auth::id())
            ->where('is_active', true)
            ->first();

        if (!$subject) {
            return response()->json(['message' => 'Materia no válida o inactiva.'], 422);
        }

        $validated['user_id'] = Auth::id();
        $validated['is_completed'] = false;
        $validated['status'] = 'pending';
        $validated['is_deleted'] = false;
        $validated['reminder'] = $request->boolean('reminder');

        $task = Task::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Tarea creada exitosamente',
                'data' => $task
            ], 201);
        }

        return redirect()->route('dashboard')->with('success', 'Tarea creada exitosamente.');
    }

    /**
     * Endpoint asíncrono para Kanban y Checkboxes (Cambio de estado)
     */
    public function update(Request $request, Task $task)
    {
        // Verificar propiedad (Seguridad)
        if ($task->user_id !== Auth::id()) {
            abort(403, 'No autorizado.');
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:200',
            'description' => 'nullable|string',
            'task_type' => 'sometimes|required|in:parcial,final,tp,normal',
            'priority' => 'sometimes|required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'is_completed' => 'sometimes|boolean',
            'status' => 'sometimes|in:pending,in_progress,completed',
            'team_members' => 'nullable|string|max:255',
            'submission_format' => 'nullable|string|max:100',
            'grade' => 'nullable|numeric|between:0,10',
            'enrollment_date' => 'nullable|date',
            'exam_type' => 'nullable|string|max:50'
        ]);

        if (isset($validated['status'])) {
            $validated['is_completed'] = ($validated['status'] === 'completed');
        } elseif (isset($validated['is_completed'])) {
            $validated['status'] = $validated['is_completed'] ? 'completed' : 'pending';
        }

        $task->update($validated);

        return response()->json([
            'message' => 'Estado de la tarea actualizado con éxito',
            'data' => $task
        ]);
    }

    /**
     * Borrado lógico de la tarea
     */
    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'No autorizado.');
        }

        $task->update(['is_deleted' => true]);

        return response()->json(['message' => 'Tarea eliminada exitosamente.']);
    }
}
