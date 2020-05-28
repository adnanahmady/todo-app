<?php

namespace Tests\Unit;

use App\Task;
use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use App\Events\TaskIsDone;
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

    /** @test */
    public function task_can_be_done()
    {
        $this->withoutExceptionHandling();
        $task = factory(Task::class)->create();

        $task->done();

        $this->assertDatabaseHas('tasks', ['finish_date' => Carbon::now()]);
    }

    /** @test */
    public function an_event_for_done_tasks_may_broadcast()
    {
        $this->withoutExceptionHandling();
        event(new TaskIsDone(
            $task = factory(Task::class)->create()
        ));

        $this->assertEventDidBroadcast(
            TaskIsDone::class,
            'presence-groups.'.$task->group->id
        );
    }
}
