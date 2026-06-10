<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Career;
use App\Models\University;

class CareerController extends Controller
{
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

    return response()->json([
        'message' => 'Carrera creada correctamente.',
        'data' => $career,
    ], 201);
}
}




