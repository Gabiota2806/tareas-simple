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

        $university = University::create([
            'name' => $validated['name'],
            'acronym' => $validated['acronym'] ?? null,
            'user_id' => $request->user()->id,
        ]);

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

        $university->delete();

        return redirect()->route('universities.index')->with('success', 'Universidad eliminada correctamente.');
    }
}


