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

        return response()->json([
            'data' => $universities,
        ]);
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

        return response()->json([
            'message' => 'Universidad creada correctamente.',
            'data' => $university,
        ], 201);
    }

}


