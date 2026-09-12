@extends('layouts.app')

@section('content')
<section class="page-heading"><div><p class="eyebrow">Visual planning</p><h1>Progress board</h1><p class="muted">Move each task forward as you make progress.</p></div><a class="button button-primary" href="{{ route('tasks.create') }}"><span aria-hidden="true">+</span> Add Task</a></section>
<div class="kanban-board">
    @foreach(['To Do' => 'Start here', 'In Progress' => 'On your desk', 'Done' => 'Completed'] as $status => $description)
        <section class="kanban-column"><div class="column-heading"><div><h2>{{ strtoupper($status) }}</h2><p>{{ $description }}</p></div><span class="column-count">{{ $tasks->where('status', $status)->count() }}</span></div>
            @forelse($tasks->where('status', $status) as $task)
                @php
                    $dueState = strtolower(str_replace(' ', '-', $task->dueStatus()));
                @endphp
                <article class="kanban-card {{ $dueState }}"><div class="card-top"><span class="priority priority-{{ strtolower($task->priority) }}">{{ $task->priority }}</span><a href="{{ route('tasks.show', $task) }}">View</a></div><h3>{{ $task->title }}</h3><p class="subject-label">{{ $task->subject }}</p><time>Due {{ $task->due_date->format('M d, Y') }} at {{ $task->dueTimeLabel() }}</time>@if($task->dueStatus() !== 'Due')<span class="due-indicator">{{ $task->dueStatus() }}</span>@endif @if($task->attachment_path)<a class="file-link" href="{{ route('tasks.attachment', $task) }}" target="_blank">View file</a>@endif<form class="status-form" method="POST" action="{{ route('tasks.status', $task) }}">@csrf @method('PATCH')<label>Move to<select name="status" onchange="this.form.submit()">@foreach(['To Do', 'In Progress', 'Done'] as $option)<option value="{{ $option }}" @selected($task->status === $option)>{{ $option }}</option>@endforeach</select></label></form></article>
            @empty
                <div class="column-empty">No tasks here yet.</div>
            @endforelse
        </section>
    @endforeach
</div>
@endsection
