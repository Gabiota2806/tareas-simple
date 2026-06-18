<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     * Endpoint asíncrono para renderizado JSON de FullCalendar
     */
    public function events(Request $request)
    {
        $userId = Auth::id();
        
        // Obtenemos solo las materias activas del usuario actual
        $subjects = Subject::where('user_id', $userId)
            ->where('is_active', true)
            ->get();
            
        $subjectIds = $subjects->pluck('id');
        
        // Filtramos las tareas cuyas materias padre están activas
        // Se excluye 'is_deleted' ya que se abordará en migraciones posteriores
        $tasks = Task::whereIn('subject_id', $subjectIds)
            ->where('user_id', $userId)
            ->with('subject')
            ->get();
            
        $events = [];
        
        foreach ($tasks as $task) {
            // FullCalendar requiere obligatoriamente una fecha de inicio
            if ($task->due_date) {
                // Generar formato ISO8601 (YYYY-MM-DD o YYYY-MM-DDTHH:MM:SS)
                $start = $task->due_date;
                if ($task->task_time) {
                    $start .= 'T' . $task->task_time;
                }
                
                $events[] = [
                    'id' => 'task_' . $task->id,
                    'title' => $task->title,
                    'start' => $start,
                    'color' => $task->subject ? $task->subject->color_code : '#8b5cf6',
                    'extendedProps' => [
                        'type' => 'task',
                        'priority' => $task->priority,
                        'is_completed' => $task->is_completed,
                    ]
                ];
            }
        }
        
        return response()->json($events);
    }
}
