<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Handle the global search.
     */
    public function index(Request $request)
    {
        $query = $request->input('q');

        // If query is empty, return empty results
        if (!$query) {
            return view('search.index', [
                'query' => $query,
                'tasks' => collect(),
                'subjects' => collect(),
            ]);
        }

        // Search Tasks
        $tasks = Task::where('user_id', Auth::id())
                     ->where(function($q) use ($query) {
                         $q->where('title', 'like', "%{$query}%")
                           ->orWhere('description', 'like', "%{$query}%");
                     })
                     ->with('subject')
                     ->get();

        // Search Subjects
        $subjects = Subject::where('user_id', Auth::id())
                           ->where(function($q) use ($query) {
                               $q->where('name', 'like', "%{$query}%")
                                 ->orWhere('teacher', 'like', "%{$query}%")
                                 ->orWhere('classroom', 'like', "%{$query}%");
                           })
                           ->get();

        return view('search.index', compact('query', 'tasks', 'subjects'));
    }
}
