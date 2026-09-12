<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Task extends Model
{
    protected $fillable = ['user_id', 'title', 'subject', 'description', 'due_date', 'due_time', 'priority', 'status', 'attachment_path'];

    protected function casts(): array
    {
        return ['due_date' => 'date'];
    }

    public function dueAt(): Carbon
    {
        return Carbon::parse($this->due_date->format('Y-m-d') . ' ' . ($this->due_time ?: '23:59:59'));
    }

    public function dueTimeLabel(): string
    {
        return $this->due_time ? Carbon::parse($this->due_time)->format('g:i A') : 'No time set';
    }

    public function dueStatus(): string
    {
        if ($this->status === 'Done') {
            return 'Completed';
        }

        if ($this->dueAt()->isPast()) {
            return 'Overdue';
        }

        return $this->dueAt()->diffInMinutes(now()) <= 24 * 60 ? 'Due Soon' : 'Due';
    }

    public function dueCountdown(): string
    {
        if ($this->status === 'Done') {
            return 'Completed';
        }

        $minutes = (int) abs($this->dueAt()->diffInMinutes(now()));
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;
        $parts = [];

        if ($hours > 0) {
            $parts[] = $hours . ' ' . ($hours === 1 ? 'hour' : 'hours');
        }
        if ($remainingMinutes > 0 || $hours === 0) {
            $parts[] = $remainingMinutes . ' ' . ($remainingMinutes === 1 ? 'minute' : 'minutes');
        }

        return $this->dueAt()->isPast()
            ? 'Overdue by ' . implode(' ', $parts)
            : 'Due in ' . implode(' ', $parts);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
