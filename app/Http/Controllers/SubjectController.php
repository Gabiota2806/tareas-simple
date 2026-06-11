<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SubjectController extends Controller
{    /**
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

        // Retornamos la vista del listado pasándole la colección de materias aisladas
        return view('subjects.index', compact('subjects'));
    }

    /**
     * Muestra el formulario para crear una nueva asignatura.
     */
    public function create()
    {
        return view('subjects.create');
    }

    /**
     * Almacena una nueva asignatura validando las restricciones físicas de la migración.
     */
    public function store(Request $request)
    {
        // Validamos ajustándonos exactamente a los límites de caracteres de la migración
        $validated = $request->validate([ 
             'name'       => 'required|string|max:150', // Coincide con $table->string('name', 150)
            'teacher'    => 'required|string|max:150', // Coincide con $table->string('teacher', 150)
            'classroom'  => 'required|string|max:50',  // Coincide con $table->string('classroom', 50)
            'color_code' => 'required|string|max:7',   // Coincide con $table->string('color_code', 7)
            'career_id'  => 'required|exists:careers,id', // Validamos que el career_id exista en la tabla careers para mantener la integridad referencial
        ]);

        // Inyectamos de forma segura el ID del usuario autenticado (Breeze)
        $validated['user_id'] = Auth::id();
        
        // Al crearse, la migración define 'is_active' como true por defecto, lo reflejamos en el flujo
        $validated['is_active'] = true;

        // Creamos la asignatura mediante asignación masiva usando el modelo oficial
        Subject::create($validated);

        return redirect()->route('subjects.index')->with('success', 'Asignatura registrada con éxito.');
    }

    /**
     * Muestra el formulario para modificar una asignatura existente.
     */
    public function edit(Subject $subject)
    {
        // Control de acceso: Si un usuario intenta editar la materia de otro, lanzamos error 403
        if ($subject->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        return view('subjects.edit', compact('subject'));
    }

    /**
     * Actualiza la asignatura validando los cambios y el parámetro de vigencia.
     */
    public function update(Request $request, Subject $subject)
    {
        // Validamos la propiedad del registro antes de operar
        if ($subject->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $validated = $request->validate([
            'name'       => 'required|string|max:150',
            'teacher'    => 'required|string|max:150',
            'classroom'  => 'required|string|max:50',
            'color_code' => 'required|string|max:7',
            'career_id'  => 'required|exists:careers,id',
            'is_active'  => 'required|boolean', // Validamos que el estado de vigencia sea un booleano para mantener la integridad de datos
        ]);

        $subject->update($validated);

        return redirect()->route('subjects.index')->with('success', 'Asignatura actualizada correctamente.');
    }

    /**
     * Elimina el recurso de la base de datos de manera protegida.
     */
    public function destroy(Subject $subject)
    {
        if ($subject->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $subject->delete();

        return redirect()->route('subjects.index')->with('success', 'Asignatura eliminada del sistema.');
    }
}