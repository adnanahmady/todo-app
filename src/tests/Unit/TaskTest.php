<?php

namespace Tests\Unit;

use App\Task;
use App\User;
use Tests\TestCase;
use App\Events\NewTaskDidCreateEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function task_event_must_broadcast()
    {
        $this->withoutExceptionHandling();

        event(new NewTaskDidCreateEvent(
            $user = factory(User::class)->create(),
            $task = factory(Task::class)->create(['owner_id' => $user->id])
        ));

        $this->assertEventDidBroadcast(
            NewTaskDidCreateEvent::class,
            'presence-groups.'.$task->group->id
        );
    }
}
