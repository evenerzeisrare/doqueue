<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Tests\TestCase;

class TaskoraFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_read_notification_stays_read_and_unread_count_disappears(): void
    {
        $user = User::factory()->create();
        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'History essay',
            'subject' => 'History',
            'due_date' => now()->toDateString(),
            'due_time' => '23:59',
            'priority' => 'Medium',
            'status' => 'To Do',
        ]);
        $notification = Notification::create([
            'user_id' => $user->id,
            'task_id' => $task->id,
            'message' => 'History essay is due soon.',
            'type' => 'approaching',
        ]);

        $this->actingAs($user)->patch(route('notifications.read', $notification))->assertRedirect();

        $this->actingAs($user)
            ->get(route('notifications.index'))
            ->assertOk()
            ->assertDontSee('notification-count');

        $this->assertTrue($notification->fresh()->is_read);
    }

    public function test_users_cannot_access_another_users_task(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::create([
            'user_id' => $owner->id,
            'title' => 'Private assignment',
            'subject' => 'Science',
            'due_date' => now()->addDay()->toDateString(),
            'priority' => 'Low',
            'status' => 'To Do',
        ]);

        $this->actingAs($otherUser)->get(route('tasks.show', $task))->assertNotFound();
        $this->actingAs($otherUser)->get(route('tasks.edit', $task))->assertNotFound();
        $this->actingAs($otherUser)->delete(route('tasks.destroy', $task))->assertNotFound();
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'user_id' => $owner->id]);
    }

    public function test_task_attachment_is_rendered_inline(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $path = UploadedFile::fake()->create('notes.pdf', 10, 'application/pdf')->store('task-attachments');
        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'View notes',
            'subject' => 'Science',
            'due_date' => now()->addDay()->toDateString(),
            'priority' => 'Low',
            'status' => 'To Do',
            'attachment_path' => $path,
        ]);

        $this->actingAs($user)->get(route('tasks.attachment', $task))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertHeader('Content-Disposition', 'inline; filename="' . basename($path) . '"');
    }
}
