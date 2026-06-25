<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class HistorialController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())
            ->active()
            ->roots()
            ->where('status', 'completed')
            ->with('subject')
            ->orderBy('completed_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('historial.index', compact('tasks'));
    }
}
