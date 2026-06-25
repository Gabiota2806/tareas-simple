<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SubjectController extends Controller
{
    /**
     * Muestra un listado de las asignaturas del usuario autenticado, filtrando por vigencia.
     */
    public function index(Request $request)
    {
        if ($request->has('is_active')) {
            session(['subject_is_active' => $request->boolean('is_active')]);
        }
        if ($request->has('career_id')) {
            session(['subject_career_id' => $request->query('career_id')]);
        }

        $isActive = session('subject_is_active', true);
        $selectedCareer = session('subject_career_id');
        $activeUniId = session('active_university_id');

        $careersQuery = \App\Models\Career::whereHas('university', function($q) {
            $q->where('user_id', Auth::id());
        });

        if ($activeUniId) {
            $careersQuery->where('university_id', $activeUniId);
        }

        $careers = $careersQuery->orderBy('name')->get();

        // Consultamos las asignaturas del usuario autenticado filtrando por el estado de vigencia y carrera si está presente
        $subjectsQuery = Subject::with('tasks')->where('user_id', Auth::id())
                           ->where('is_active', $isActive);

        if ($activeUniId) {
            $subjectsQuery->whereHas('career', function($q) use ($activeUniId) {
                $q->where('university_id', $activeUniId);
            });
        }

        if ($selectedCareer) {
            $subjectsQuery->where('career_id', $selectedCareer);
        }

        $subjects = $subjectsQuery->get();

        return view('subjects.index', compact('subjects', 'isActive', 'careers', 'selectedCareer'));
    }

    /**
     * Muestra el formulario para crear una nueva asignatura.
     */
    public function create()
    {
        $activeUniId = session('active_university_id');
        $careersQuery = \App\Models\Career::whereHas('university', function($q) {
            $q->where('user_id', Auth::id());
        });

        if ($activeUniId) {
            $careersQuery->where('university_id', $activeUniId);
        }

        $careers = $careersQuery->get();
        $selectedCareer = session('subject_career_id');
        
        return view('subjects.create', compact('careers', 'selectedCareer'));
    }

    /**
     * Almacena una nueva asignatura validando las restricciones físicas de la migración.
     */
    public function store(Request $request)
    {
        // Validamos ajustándonos exactamente a los límites de caracteres de la migración
        $validated = $request->validate([
            'name'       => 'required|string|max:150',   // Coincide con $table->string('name', 150)
            'teacher'    => 'nullable|string|max:150',    // Coincide con $table->string('teacher', 150)->nullable()
            'classroom'  => 'nullable|string|max:50',     // Coincide con $table->string('classroom', 50)->nullable()
            'color_code' => 'required|string|max:7',
            'career_id'  => 'required|exists:careers,id',
            'approval_type' => 'nullable|string|in:promocional,regular,libre',
            'final_grade' => 'nullable|numeric|min:0|max:10',
        ], [
            'career_id.required' => 'Debes seleccionar una carrera a la cual pertenezca la asignatura.',
            'career_id.exists'   => 'La carrera seleccionada no es válida.',
            'name.required'      => 'El nombre de la asignatura es obligatorio.'
        ]);

        $request->validate([
            'schedules' => 'nullable|array',
            'schedules.*.day_of_week' => 'required_with:schedules|integer|between:0,6',
            'schedules.*.start_time' => 'required_with:schedules|date_format:H:i',
            'schedules.*.end_time' => 'required_with:schedules|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.classroom' => 'nullable|string|max:50',
        ]);

        // Inyectamos de forma segura el ID del usuario autenticado (Breeze)
        $validated['user_id'] = Auth::id();

        // Al crearse, la migración define 'is_active' como true por defecto, lo reflejamos en el flujo
        $validated['is_active'] = true;

        // Creamos la asignatura mediante asignación masiva usando el modelo oficial
        $subject = Subject::create($validated);

        if ($request->filled('schedules')) {
            $subject->schedules()->createMany($request->schedules);
        }

        return redirect('/subjects')->with('success', 'Asignatura registrada con éxito.');
    }

    public function edit(Request $request, Subject $subject)
    {
        if ($subject->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $activeUniId = session('active_university_id');
        $careersQuery = \App\Models\Career::whereHas('university', function($q) {
            $q->where('user_id', Auth::id());
        });

        if ($activeUniId) {
            $careersQuery->where('university_id', $activeUniId);
        }

        $careers = $careersQuery->get();
        return view('subjects.edit', compact('subject', 'careers'));
    }

    /**
     * Alterna el estado de vigencia de una asignatura (activo/inactivo).
     */
    public function update(Request $request, Subject $subject)
    {
        if ($subject->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $validated = $request->validate([
            'name'       => 'sometimes|required|string|max:150',
            'teacher'    => 'nullable|string|max:150',
            'classroom'  => 'nullable|string|max:50',
            'color_code' => 'sometimes|required|string|max:7',
            'career_id'  => 'sometimes|required|exists:careers,id',
            'is_active'  => 'sometimes|boolean',
            'approval_type' => 'nullable|string|in:promocional,regular,libre',
            'final_grade' => 'nullable|numeric|min:0|max:10',
        ], [
            'career_id.required' => 'Debes seleccionar una carrera a la cual pertenezca la asignatura.',
            'career_id.exists'   => 'La carrera seleccionada no es válida.',
            'name.required'      => 'El nombre de la asignatura es obligatorio.'
        ]);

        if ($request->has('update_schedules')) {
            $request->validate([
                'schedules' => 'nullable|array',
                'schedules.*.day_of_week' => 'required_with:schedules|integer|between:0,6',
                'schedules.*.start_time' => 'required_with:schedules|date_format:H:i',
                'schedules.*.end_time' => 'required_with:schedules|date_format:H:i|after:schedules.*.start_time',
                'schedules.*.classroom' => 'nullable|string|max:50',
            ]);
        }

        $subject->update($validated);

        if ($request->has('update_schedules')) {
            $subject->schedules()->delete();
            if ($request->filled('schedules')) {
                $subject->schedules()->createMany($request->schedules);
            }
        }

        return redirect()->route('subjects.index')->with('success', 'Materia actualizada correctamente.');
    }

    /**
     * Elimina lógicamente la asignatura marcándola como inactiva.
     */
    public function destroy(Subject $subject)
    {
        // Control de acceso: Si un usuario intenta eliminar la materia de otro, lanzamos error 403
        if ($subject->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        // Borrado lógico: marcamos como inactiva en lugar de eliminar el registro
        $subject->update(['is_active' => false]);

        return redirect()->route('subjects.index')->with('success', 'Asignatura archivada correctamente.');
    }

    public function show(Subject $subject)
    {
        if ($subject->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $tasks = $subject->tasks()
            ->active()
            ->whereNotIn('task_type', ['parcial', 'final'])
            ->where(function($query) {
                $query->where('status', '!=', 'completed')
                      ->orWhere('updated_at', '>=', now()->subDays(15));
            })
            ->byPriority()
            ->get();

        return view('subjects.show', compact('subject', 'tasks'));
    }
}