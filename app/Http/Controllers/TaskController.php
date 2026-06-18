<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
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
}
