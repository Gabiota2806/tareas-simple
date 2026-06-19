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
        // Obtenemos el parámetro de vigencia desde la consulta, por defecto mostramos solo las activas (is_active = 1)
        $isActive = $request->boolean('is_active', true);

        // Consultamos las asignaturas del usuario autenticado filtrando por el estado de vigencia
        $subjects = Subject::where('user_id', Auth::id())
                           ->where('is_active', $isActive)
                           ->get();

        return view('subjects.index', compact('subjects', 'isActive'));
    }

    /**
     * Muestra el formulario para crear una nueva asignatura.
     */
    public function create()
    {
        $careers = \App\Models\Career::whereHas('university', function($q) {
            $q->where('user_id', Auth::id());
        })->get();
        return view('subjects.create', compact('careers'));
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
            'color_code' => 'required|string|max:7',      // Coincide con $table->string('color_code', 7)
            'career_id'  => 'required|exists:careers,id', // Validamos que el career_id exista en la tabla careers
        ]);

        // Inyectamos de forma segura el ID del usuario autenticado (Breeze)
        $validated['user_id'] = Auth::id();

        // Al crearse, la migración define 'is_active' como true por defecto, lo reflejamos en el flujo
        $validated['is_active'] = true;

        // Creamos la asignatura mediante asignación masiva usando el modelo oficial
        $subject = Subject::create($validated);

        return redirect('/subjects')->with('success', 'Asignatura registrada con éxito.');
    }

    public function edit(Request $request, Subject $subject)
    {
        if ($subject->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $careers = \App\Models\Career::whereHas('university', function($q) {
            $q->where('user_id', Auth::id());
        })->get();
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
            'name'       => 'required|string|max:150',
            'teacher'    => 'nullable|string|max:150',
            'classroom'  => 'nullable|string|max:50',
            'color_code' => 'required|string|max:7',
            'career_id'  => 'required|exists:careers,id',
            'is_active'  => 'sometimes|boolean',
        ]);

        $subject->update($validated);

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
}