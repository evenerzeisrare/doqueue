<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Subject;
use App\Services\TaskReminderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        return view('tasks.index', ['tasks' => Task::where('user_id', Auth::id())->latest('due_date')->get()]);
    }

    public function create()
    {
        return view('tasks.create', ['subjects' => Subject::where('user_id', Auth::id())->orderBy('name')->get()]);
    }

    public function store(Request $request, TaskReminderService $reminders)
    {
        $data = $this->validated($request);
        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('task-attachments');
        }
        $data['user_id'] = Auth::id();
        Task::create($data);
        $reminders->sync();

        return redirect()->route('tasks.index')->with('success', 'Task added successfully.');
    }

    public function edit(Task $task)
    {
        $this->ensureOwner($task);
        return view('tasks.edit', ['task' => $task, 'subjects' => Subject::where('user_id', Auth::id())->orderBy('name')->get()]);
    }

    public function show(Task $task)
    {
        $this->ensureOwner($task);
        return view('tasks.show', compact('task'));
    }

    public function preview(Task $task)
    {
        $this->ensureOwner($task);
        abort_unless($task->attachment_path && Storage::exists($task->attachment_path), 404);
        $path = Storage::path($task->attachment_path);
        $mimeType = Storage::mimeType($task->attachment_path) ?: 'application/octet-stream';

        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . addslashes(basename($task->attachment_path)) . '"',
        ]);
    }

    public function download(Task $task)
    {
        $this->ensureOwner($task);
        abort_unless($task->attachment_path && Storage::exists($task->attachment_path), 404);

        return Storage::download($task->attachment_path, basename($task->attachment_path));
    }

    public function update(Request $request, Task $task, TaskReminderService $reminders)
    {
        $this->ensureOwner($task);
        $data = $this->validated($request);
        if ($request->hasFile('attachment')) {
            if ($task->attachment_path) {
                Storage::delete($task->attachment_path);
            }
            $data['attachment_path'] = $request->file('attachment')->store('task-attachments');
        }
        $task->update($data);
        $reminders->sync();

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task, TaskReminderService $reminders)
    {
        $this->ensureOwner($task);
        if ($task->attachment_path) {
            Storage::delete($task->attachment_path);
        }
        $task->delete();
        $reminders->sync();

        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'subject' => ['required', 'string', 'max:80', 'exists:subjects,name,user_id,' . Auth::id()],
            'description' => ['nullable', 'string', 'max:1000'],
            'due_date' => ['required', 'date'],
            'due_time' => ['nullable', 'date_format:H:i'],
            'attachment' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,png,jpg,jpeg,gif,txt,ppt,pptx,xls,xlsx'],
            'priority' => ['required', 'in:Low,Medium,High'],
            'status' => ['required', 'in:To Do,In Progress,Done'],
        ]);
    }

    private function ensureOwner(Task $task): void
    {
        abort_unless($task->user_id === Auth::id(), 404);
    }
}
