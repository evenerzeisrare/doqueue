<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressBoardController extends Controller
{
    public function index()
    {
        return view('kanban', ['tasks' => Task::where('user_id', Auth::id())->orderBy('due_date')->get()]);
    }

    public function updateStatus(Request $request, Task $task)
    {
        abort_unless($task->user_id === Auth::id(), 404);
        $validated = $request->validate(['status' => ['required', 'in:To Do,In Progress,Done']]);
        $task->update($validated);

        return back()->with('success', 'Task status updated.');
    }
}