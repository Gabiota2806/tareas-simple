<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Career;
use App\Models\University;

class CareerController extends Controller
{

    public function index(Request $request)
    {
        $universities = University::where('user_id', $request->user()->id)->orderBy('name')->get();
        $selectedUniversity = $request->query('university_id');

        $careersQuery = Career::whereHas('university', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->with('university');

        if ($selectedUniversity) {
            $careersQuery->where('university_id', $selectedUniversity);
        }

        $careers = $careersQuery->orderBy('name')->get();

        return view('careers.index', compact('careers', 'universities', 'selectedUniversity'));
    }

    public function create(Request $request)
    {
        $universities = University::where('user_id', $request->user()->id)->get();
        return view('careers.create', compact('universities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'duration_years' => ['nullable', 'integer', 'min:1', 'max:20'],
            'university_id' => ['required', 'exists:universities,id'],
        ]);

        $university = University::where('id', $validated['university_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $career = Career::create([
            'name' => $validated['name'],
            'duration_years' => $validated['duration_years'] ?? null,
            'university_id' => $university->id,
        ]);

        return redirect()->route('careers.index')->with('success', 'Carrera creada correctamente.');
    }

    public function edit(Request $request, Career $career)
    {
        $career->load('university');
        if ($career->university->user_id !== $request->user()->id) {
            abort(403, 'Acción no autorizada.');
        }

        $universities = University::where('user_id', $request->user()->id)->get();
        return view('careers.edit', compact('career', 'universities'));
    }

    public function update(Request $request, Career $career)
    {
        $career->load('university');
        if ($career->university->user_id !== $request->user()->id) {
            abort(403, 'Acción no autorizada.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'duration_years' => ['nullable', 'integer', 'min:1', 'max:20'],
            'university_id' => ['required', 'exists:universities,id'],
        ]);

        $university = University::where('id', $validated['university_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $career->update([
            'name' => $validated['name'],
            'duration_years' => $validated['duration_years'],
            'university_id' => $university->id,
        ]);

        return redirect()->route('careers.index')->with('success', 'Carrera actualizada correctamente.');
    }

    public function destroy(Request $request, Career $career)
    {
        $career->load('university');
        if ($career->university->user_id !== $request->user()->id) {
            abort(403, 'Acción no autorizada.');
        }

        $career->delete();

        return redirect()->route('careers.index')->with('success', 'Carrera eliminada correctamente.');
    }
}




