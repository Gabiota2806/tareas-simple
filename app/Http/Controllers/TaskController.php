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
            'parent_id' => 'nullable|exists:tasks,id'
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
        $validated['is_deleted'] = false;
        $validated['reminder'] = $request->boolean('reminder');

        $task = Task::create($validated);

        return response()->json([
            'message' => 'Tarea creada exitosamente',
            'data' => $task
        ], 201);
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
            'is_completed' => 'sometimes|boolean',
            // Agregamos task_type si el Kanban cambia de estado de tipo de tarea, 
            // pero el contrato Kanban dice "Pendiente -> Completado", lo cual se maneja con is_completed.
        ]);

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
