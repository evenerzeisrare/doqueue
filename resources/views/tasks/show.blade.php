@extends('layouts.app')

@section('content')
<section class="page-heading"><div><p class="eyebrow">Task details</p><h1>{{ $task->title }}</h1><p class="muted">{{ $task->subject }}</p></div><div class="heading-actions"><a class="button button-muted" href="{{ route('tasks.index') }}">Back to tasks</a><a class="button button-primary" href="{{ route('tasks.edit', $task) }}">Edit task</a></div></section>
<section class="detail-panel">
    <div class="detail-grid">
        <div class="detail-item"><span>Status</span><strong class="due-status status-{{ strtolower(str_replace(' ', '-', $task->dueStatus())) }}">{{ $task->dueStatus() }}</strong></div>
        <div class="detail-item"><span>Priority</span><strong>{{ $task->priority }}</strong></div>
        <div class="detail-item"><span>Due date</span><strong>{{ $task->due_date->format('M d, Y') }}</strong></div>
        <div class="detail-item"><span>Due time</span><strong>{{ $task->dueTimeLabel() }}</strong></div>
    </div>
    <div class="detail-section"><p class="eyebrow">Time remaining</p><p class="countdown {{ strtolower(str_replace(' ', '-', $task->dueStatus())) }}">{{ $task->dueCountdown() }}</p></div>
    <div class="detail-section"><p class="eyebrow">Description</p><p class="detail-description">{{ $task->description ?: 'No description added.' }}</p></div>
    @if($task->attachment_path)<div class="detail-section attachment-actions"><p class="eyebrow">Attachment</p><a class="button button-muted" href="{{ route('tasks.attachment', $task) }}" target="_blank">View {{ basename($task->attachment_path) }}</a><a class="button button-muted" href="{{ route('tasks.attachment.download', $task) }}">Download</a></div>@endif
</section>
@endsection
