<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\University;

class UniversityController extends Controller
{
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

    return response()->json([
        'message' => 'Universidad creada correctamente.',
        'data' => $university,
    ], 201);
}

}


