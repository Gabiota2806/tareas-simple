<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('career_id')) {
            session(['calendar_career_id' => $request->query('career_id')]);
        }

        $activeUniId = session('active_university_id');

        $careers = \App\Models\Career::where('university_id', $activeUniId)
            ->whereHas('university', fn($q) => $q->where('user_id', Auth::id()))
            ->orderBy('name')
            ->get();
            
        $selectedCareer = session('calendar_career_id');
        
        // Forzar siempre una carrera seleccionada (la primera disponible) si no hay ninguna
        if (!$selectedCareer && $careers->isNotEmpty()) {
            $selectedCareer = $careers->first()->id;
            session(['calendar_career_id' => $selectedCareer]);
        }

        return view('calendar.index', compact('careers', 'selectedCareer'));
    }

    /**
     * Endpoint asíncrono para renderizado JSON de FullCalendar
     */
    public function events(Request $request)
    {
        $userId = Auth::id();
        $activeUniId = session('active_university_id');
        $careerId = session('calendar_career_id');
        
        // Asegurar que siempre hay una carrera seleccionada en los eventos también
        if (!$careerId) {
            $firstCareer = \App\Models\Career::where('university_id', $activeUniId)
                ->whereHas('university', fn($q) => $q->where('user_id', $userId))
                ->orderBy('name')
                ->first();
            if ($firstCareer) {
                $careerId = $firstCareer->id;
                session(['calendar_career_id' => $careerId]);
            }
        }
        
        $query = Subject::where('user_id', $userId)
            ->where('is_active', true)
            ->whereHas('career', function($q) use ($activeUniId) {
                $q->where('university_id', $activeUniId);
            });
            
        if ($careerId) {
            $query->where('career_id', $careerId);
        }
            
        $subjects = $query->get();
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
                $start = $task->due_date->format('Y-m-d');
                if ($task->task_time) {
                    $start .= 'T' . $task->task_time;
                }
                
                $isExam = in_array($task->task_type, ['parcial', 'final']);
                
                $events[] = [
                    'id' => 'task_' . $task->id,
                    'title' => ($isExam ? '📝 ' : '✅ ') . $task->title,
                    'start' => $start,
                    'color' => $task->subject ? $task->subject->color_code : '#8b5cf6',
                    'extendedProps' => [
                        'type' => $isExam ? 'exam' : 'task',
                        'priority' => $task->priority,
                        'is_completed' => $task->is_completed,
                        'description' => $task->description,
                    ]
                ];
            }
        }
        
        // Agregar horarios de cursada como eventos recurrentes
        $schedules = \App\Models\SubjectSchedule::whereIn('subject_id', $subjectIds)->with('subject')->get();
        
        foreach ($schedules as $schedule) {
            $events[] = [
                'id' => 'schedule_' . $schedule->id,
                'title' => '🏫 ' . ($schedule->subject ? $schedule->subject->name : 'Clase'),
                'startTime' => $schedule->start_time,
                'endTime' => $schedule->end_time,
                'daysOfWeek' => [(int)$schedule->day_of_week],
                'color' => $schedule->subject ? $schedule->subject->color_code : '#8b5cf6',
                'extendedProps' => [
                    'type' => 'class',
                    'description' => 'Clase regular. Aula: ' . ($schedule->classroom ?? 'No asignada'),
                ]
            ];
        }
        
        return response()->json($events);
    }
}
