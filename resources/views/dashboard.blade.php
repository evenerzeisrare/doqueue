@extends('layouts.app')

@section('content')
<section class="page-heading dashboard-heading">
    <div>
        <p class="eyebrow">Student workspace</p>
        <h1>Good day, {{ $studentName }}!</h1>
        <p class="muted">Keep your schoolwork moving, one task at a time.</p>
    </div>
    <a class="button button-primary" href="{{ route('tasks.create') }}"><span aria-hidden="true">+</span> Add Task</a>
</section>

<section class="stats-grid" aria-label="Task summary">
    <div class="stat-card stat-total"><span class="stat-label">Total tasks</span><strong>{{ $totalTasks }}</strong><span class="stat-note">Everything on your list</span></div>
    <div class="stat-card"><span class="stat-label">To Do</span><strong>{{ $todoTasks }}</strong><span class="stat-note">Ready to get started</span></div>
    <div class="stat-card"><span class="stat-label">In Progress</span><strong>{{ $inProgressTasks }}</strong><span class="stat-note">Currently underway</span></div>
    <div class="stat-card"><span class="stat-label">Done</span><strong>{{ $doneTasks }}</strong><span class="stat-note">Nice work</span></div>
</section>

<div class="dashboard-grid">
    <section class="panel">
        <div class="panel-heading"><div><p class="eyebrow">Your schedule</p><h2>Upcoming tasks</h2></div><a class="text-link" href="{{ route('tasks.index') }}">View all</a></div>
        @forelse($upcomingTasks as $task)
            <article class="task-row">
                <div class="task-row-main"><span class="status-dot status-{{ Str::slug($task->status) }}"></span><div><h3>{{ $task->title }}</h3><p>{{ $task->subject }}</p></div></div>
                <div class="task-row-meta"><span class="priority priority-{{ strtolower($task->priority) }}">{{ $task->priority }}</span><time>{{ $task->due_date->format('M d, Y') }} at {{ $task->dueTimeLabel() }}</time></div>
            </article>
        @empty
            <div class="empty-state"><strong>Your task list is clear.</strong><p>Add an assignment or activity to see it here.</p></div>
        @endforelse
    </section>

    <section class="panel reminder-panel">
        <div class="panel-heading"><div><p class="eyebrow">Stay on track</p><h2>Reminders</h2></div><a class="text-link" href="{{ route('notifications.index') }}">See all</a></div>
        @forelse($notifications as $notification)
            <div class="reminder-item"><span class="reminder-icon {{ $notification->type === 'overdue' ? 'warning' : '' }}">{{ $notification->type === 'overdue' ? '!' : 'i' }}</span><div><strong>{{ match($notification->type) { 'due_tomorrow' => 'Due Tomorrow', 'approaching' => 'Due Soon', default => 'Overdue' } }}</strong><p>{{ $notification->message }}</p></div></div>
        @empty
            <div class="empty-state"><strong>No reminders yet.</strong><p>We will show due dates here when they need your attention.</p></div>
        @endforelse
    </section>
</div>
@endsection
