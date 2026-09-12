<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgressBoardTest extends TestCase
{
    use RefreshDatabase;

    public function test_progress_board_renders_each_task_once_with_due_indicator(): void
    {
        $user = User::factory()->create();
        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'Unique board task',
            'subject' => 'Mathematics',
            'due_date' => now()->toDateString(),
            'due_time' => now()->subMinute()->format('H:i'),
            'priority' => 'Medium',
            'status' => 'To Do',
        ]);

        $response = $this->actingAs($user)->get(route('progress-board'));

        $response->assertOk()
            ->assertSee('Unique board task', false)
            ->assertSee('Overdue', false);
        $this->assertSame(1, substr_count($response->getContent(), 'Unique board task'));
        $this->assertSame(1, substr_count($response->getContent(), 'kanban-card'));
    }
}
