<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\TaskReminderService;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(TaskReminderService $reminders)
    {
        $reminders->sync();

        return view('notifications.index', ['notifications' => Notification::with('task')->where('user_id', Auth::id())->latest()->get()]);
    }

    public function markRead(Notification $notification)
    {
        abort_unless($notification->user_id === Auth::id(), 404);
        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notification marked as read.');
    }
}
