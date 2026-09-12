@extends('layouts.app')

@section('content')
<section class="page-heading"><div><p class="eyebrow">Keep an eye on deadlines</p><h1>Notifications</h1><p class="muted">Helpful reminders generated from your task due dates.</p></div></section>
<section class="panel notification-list">
    @forelse($notifications as $notification)
        <article class="notification-item {{ $notification->is_read ? 'read' : 'unread' }}"><span class="reminder-icon {{ $notification->type === 'overdue' ? 'warning' : '' }}">{{ $notification->type === 'overdue' ? '!' : 'i' }}</span><div class="notification-copy"><div class="notification-title"><strong>{{ match($notification->type) { 'due_tomorrow' => 'Due Tomorrow', 'approaching' => 'Due Soon', default => 'Overdue' } }}</strong><span>{{ $notification->created_at->format('M d, Y') }}</span></div><p>{{ $notification->message }}</p><small>Related task: {{ $notification->task?->title ?? 'Task removed' }}</small></div>@if(!$notification->is_read)<form method="POST" action="{{ route('notifications.read', $notification) }}">@csrf @method('PATCH')<button class="button button-small button-muted" type="submit">Mark read</button></form>@else<span class="read-label">Read</span>@endif</article>
    @empty
        <div class="empty-state large"><strong>You are all caught up.</strong><p>Notifications will appear when a task is due today, tomorrow, or overdue.</p></div>
    @endforelse
</section>
@endsection
