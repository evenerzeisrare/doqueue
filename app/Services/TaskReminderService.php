<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TaskReminderService
{
    public function sync(): void
    {
        $activeNotificationIds = [];
        $now = Carbon::now();
        $userId = Auth::id();

        Task::where('user_id', $userId)->each(function (Task $task) use ($now, $userId, &$activeNotificationIds): void {
            if ($task->status === 'Done') {
                Notification::where('user_id', $userId)->where('task_id', $task->id)->delete();
                return;
            }

            $dueAt = Carbon::parse($task->due_date->format('Y-m-d') . ' ' . ($task->due_time ?: '23:59:59'));
            $type = match (true) {
                $dueAt->isPast() => 'overdue',
                $dueAt->isTomorrow() => 'due_tomorrow',
                $dueAt->diffInHours($now) <= 24 => 'approaching',
                default => null,
            };

            if ($type === null) {
                Notification::where('user_id', $userId)->where('task_id', $task->id)->delete();
                return;
            }

            $label = match ($type) {
                'due_tomorrow' => 'due tomorrow',
                'approaching' => 'Due Soon',
                default => 'overdue',
            };

            $notification = Notification::firstOrCreate(
                ['user_id' => $userId, 'task_id' => $task->id, 'type' => $type],
                ['message' => $task->title . ' is ' . strtolower($label) . '.', 'is_read' => false],
            );
            $notification->update(['message' => $task->title . ' is ' . strtolower($label) . '.']);

            $activeNotificationIds[] = $notification->id;
        });

        Notification::where('user_id', $userId)->whereNotIn('id', $activeNotificationIds)->delete();
    }
}
