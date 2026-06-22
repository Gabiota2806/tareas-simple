<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\University;

class UniversityController extends Controller
{

    public function index(Request $request)
    {
        $universities = University::where('user_id', $request->user()->id)
            ->orderBy('name')
            ->get();

        return view('universities.index', compact('universities'));
    }

    public function create()
    {
        return view('universities.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'acronym' => ['nullable', 'string', 'max:20'],
        ]);

        $isFirst = University::where('user_id', $request->user()->id)->count() === 0;

        $university = University::create([
            'name' => $validated['name'],
            'acronym' => $validated['acronym'] ?? null,
            'user_id' => $request->user()->id,
            'is_favorite' => $isFirst,
        ]);

        if ($isFirst) {
            session(['active_university_id' => $university->id]);
            session(['active_university_name' => $university->name]);
            return redirect()->route('dashboard')->with('success', '¡Bienvenido! Has creado tu primera universidad.');
        }

        return redirect()->route('universities.index')->with('success', 'Universidad creada correctamente.');
    }

    public function edit(Request $request, University $university)
    {
        if ($university->user_id !== $request->user()->id) {
            abort(403, 'Acción no autorizada.');
        }

        return view('universities.edit', compact('university'));
    }

    public function update(Request $request, University $university)
    {
        if ($university->user_id !== $request->user()->id) {
            abort(403, 'Acción no autorizada.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'acronym' => ['nullable', 'string', 'max:20'],
        ]);

        $university->update($validated);

        return redirect()->route('universities.index')->with('success', 'Universidad actualizada correctamente.');
    }

    public function destroy(Request $request, University $university)
    {
        if ($university->user_id !== $request->user()->id) {
            abort(403, 'Acción no autorizada.');
        }

        $wasFavorite = $university->is_favorite;
        $isActive = session('active_university_id') == $university->id;
        
        $university->delete();

        if ($wasFavorite) {
            $nextUni = University::where('user_id', $request->user()->id)->first();
            if ($nextUni) {
                $nextUni->update(['is_favorite' => true]);
                session(['active_university_id' => $nextUni->id]);
                session(['active_university_name' => $nextUni->name]);
            } else {
                session()->forget(['active_university_id', 'active_university_name']);
            }
        } elseif ($isActive) {
            $fav = University::where('user_id', $request->user()->id)->where('is_favorite', true)->first();
            if ($fav) {
                session(['active_university_id' => $fav->id]);
                session(['active_university_name' => $fav->name]);
            } else {
                session()->forget(['active_university_id', 'active_university_name']);
            }
        }

        return redirect()->route('universities.index')->with('success', 'Universidad eliminada correctamente.');
    }

    public function toggleFavorite(Request $request, University $university)
    {
        if ($university->user_id !== $request->user()->id) {
            abort(403, 'Acción no autorizada.');
        }

        if (!$university->is_favorite) {
            // Desmarcar todas las otras universidades
            University::where('user_id', $request->user()->id)->update(['is_favorite' => false]);
            // Marcar esta como favorita
            $university->update(['is_favorite' => true]);
            
            // Auto-seleccionar en la sesión
            session(['active_university_id' => $university->id]);
            session(['active_university_name' => $university->name]);
            return back();
        }

        // Si ya es favorita, no hace nada
        return back();
    }
}


