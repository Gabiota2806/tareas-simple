<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicRecordController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('career_id')) {
            session(['subject_career_id' => $request->query('career_id')]);
        }

        $activeUniId = session('active_university_id');
        $selectedCareer = session('subject_career_id');

        // Obtener materias de la universidad activa
        $subjectsQuery = Subject::where('user_id', Auth::id())
            ->whereHas('career', function($q) use ($activeUniId) {
                $q->where('university_id', $activeUniId);
            })
            ->orderBy('name', 'asc')
            ->orderBy('id', 'asc')
            ->with(['tasks' => function($q) {
                $q->active()
                  ->visible()
                  ->whereIn('task_type', ['parcial', 'final'])
                  ->orderBy('due_date', 'asc')
                  ->orderBy('id', 'asc')
                  ->with('nestedSubtasks');
            }]);

        if ($selectedCareer) {
            $subjectsQuery->where('career_id', $selectedCareer);
        }

        $subjects = $subjectsQuery->get();

        $careers = \App\Models\Career::where('university_id', $activeUniId)
            ->whereHas('university', fn($q) => $q->where('user_id', Auth::id()))
            ->orderBy('name')
            ->get();

        return view('academic_record.index', compact('subjects', 'careers', 'selectedCareer'));
    }
}
