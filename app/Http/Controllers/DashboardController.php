<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Task;
use App\Services\TaskReminderService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(TaskReminderService $reminders)
    {
        $reminders->sync();

        $tasks = Task::where('user_id', Auth::id());

        return view('dashboard', [
            'studentName' => Auth::user()->name,
            'totalTasks' => (clone $tasks)->count(),
            'todoTasks' => (clone $tasks)->where('status', 'To Do')->count(),
            'inProgressTasks' => (clone $tasks)->where('status', 'In Progress')->count(),
            'doneTasks' => (clone $tasks)->where('status', 'Done')->count(),
            'upcomingTasks' => (clone $tasks)->whereDate('due_date', '>=', Carbon::today())->orderBy('due_date')->take(5)->get(),
            'notifications' => Notification::with('task')->where('user_id', Auth::id())->latest()->take(4)->get(),
        ]);
    }
}
