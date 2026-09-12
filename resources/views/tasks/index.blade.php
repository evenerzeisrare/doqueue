@extends('layouts.app')

@section('content')
<section class="page-heading"><div><p class="eyebrow">Your workspace</p><h1>My tasks</h1><p class="muted">All your assignments and activities in one place.</p></div><a class="button button-primary" href="{{ route('tasks.create') }}"><span aria-hidden="true">+</span> Add Task</a></section>
<section class="panel table-panel">
    @forelse($tasks as $task)
        <article class="task-list-item">
            <div class="task-list-title"><span class="status-dot status-{{ Str::slug($task->status) }}"></span><div><h2>{{ $task->title }}</h2><p>{{ $task->subject }} @if($task->description) · {{ Str::limit($task->description, 70) }} @endif</p></div></div>
                <div class="task-list-details"><span class="priority priority-{{ strtolower($task->priority) }}">{{ $task->priority }}</span><span class="status-pill">{{ $task->dueStatus() }}</span><time>{{ $task->due_date->format('M d, Y') }} at {{ $task->dueTimeLabel() }}</time>@if($task->attachment_path)<a class="file-link" href="{{ route('tasks.attachment', $task) }}" target="_blank">View file</a>@endif</div>
            <div class="task-actions"><a class="button button-small button-muted" href="{{ route('tasks.show', $task) }}">View</a><a class="button button-small button-muted" href="{{ route('tasks.edit', $task) }}">Edit</a><form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?');">@csrf @method('DELETE')<button class="button button-small button-danger" type="submit">Delete</button></form></div>
        </article>
    @empty
        <div class="empty-state large"><strong>No tasks yet.</strong><p>Start with your next assignment or school activity.</p><a class="button button-primary" href="{{ route('tasks.create') }}">Add your first task</a></div>
    @endforelse
</section>
@endsection
